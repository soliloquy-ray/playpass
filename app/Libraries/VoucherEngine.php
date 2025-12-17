<?php

namespace App\Libraries;

use App\Models\VoucherCodeModel;
use App\Models\VoucherCampaignModel;
use App\Models\VoucherUsageLogModel;

class VoucherEngine
{
    protected $voucherModel;
    protected $campaignModel;
    protected $usageLogModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherCodeModel();
        $this->campaignModel = new VoucherCampaignModel();
        $this->usageLogModel = new VoucherUsageLogModel();
    }

    /**
     * Comprehensive voucher validation and application
     * Handles all edge cases: product applicability, per-user limits, timezone, negative totals, etc.
     * 
     * @param string $code The voucher code
     * @param float $currentCartTotal Current cart subtotal
     * @param array $cartProducts Array of product IDs in cart
     * @param int|null $userId User ID (required for per-user limits)
     * @param array $existingVouchers Already applied vouchers
     * @return array Result with success status, message, and discount data
     */
    public function applyVoucher(
        string $code, 
        float $currentCartTotal, 
        array $cartProducts = [], 
        ?int $userId = null,
        array $existingVouchers = []
    ) {
        // 1. Fetch Voucher Code and Campaign
        $voucher = $this->voucherModel->getValidVoucherByCode($code);
        
        if (!$voucher) {
            return ['success' => false, 'message' => 'Invalid or expired voucher code.'];
        }

        // Get full campaign data
        $campaign = $this->campaignModel->find($voucher['campaign_id']);
        if (!$campaign) {
            return ['success' => false, 'message' => 'Voucher campaign not found.'];
        }

        // 2. Timezone-aware date validation
        if (!$this->campaignModel->isActive($campaign)) {
            return ['success' => false, 'message' => 'This voucher is not currently valid.'];
        }

        // 3. Check if voucher code is already used (for unique vouchers)
        if ($campaign['code_type'] === 'unique_batch' && $voucher['is_redeemed']) {
            return ['success' => false, 'message' => 'This voucher code has already been used.'];
        }

        // 4. Check total usage limit (for universal vouchers)
        if ($campaign['total_usage_limit'] !== null) {
            $db = \Config\Database::connect();
            $usedCount = $db->table('voucher_usage_log')
                           ->where('campaign_id', $campaign['id'])
                           ->countAllResults();
            
            if ($usedCount >= $campaign['total_usage_limit']) {
                return ['success' => false, 'message' => 'This voucher has reached its maximum usage limit.'];
            }
        }

        // 5. Check per-user usage limit
        if ($userId !== null && $campaign['usage_limit_per_user'] > 0) {
            $userUsageCount = $this->usageLogModel->getUserUsageCount($userId, $campaign['id']);
            if ($userUsageCount >= $campaign['usage_limit_per_user']) {
                return ['success' => false, 'message' => 'You have reached the maximum usage limit for this voucher.'];
            }
        }

        // 6. Check minimum spend requirement
        if ($currentCartTotal < $campaign['min_spend_amount']) {
            return [
                'success' => false, 
                'message' => 'Minimum spend of ₱' . number_format($campaign['min_spend_amount'], 2) . ' required to use this voucher.'
            ];
        }

        // 7. Check product applicability
        $applicableProducts = $this->campaignModel->getApplicableProducts($campaign['id']);
        if (!empty($applicableProducts)) {
            // Voucher is restricted to specific products
            $cartProductIds = array_map('intval', $cartProducts);
            $applicableProductIds = array_map('intval', $applicableProducts);
            $hasApplicableProduct = !empty(array_intersect($cartProductIds, $applicableProductIds));
            
            if (!$hasApplicableProduct) {
                return ['success' => false, 'message' => 'This voucher does not apply to the products in your cart.'];
            }
        }

        // 8. Check stacking rules
        if ($campaign['is_stackable'] == 0 && count($existingVouchers) > 0) {
            return ['success' => false, 'message' => 'This voucher cannot be used with other promotions.'];
        }

        // Check if any existing voucher forbids stacking
        foreach ($existingVouchers as $applied) {
            if (isset($applied['is_stackable']) && $applied['is_stackable'] == 0) {
                return ['success' => false, 'message' => 'An exclusive voucher is already applied.'];
            }
        }

        // 9. Calculate discount amount
        $discount = 0;
        if ($campaign['discount_type'] === 'fixed_amount') {
            $discount = (float)$campaign['discount_value'];
        } elseif ($campaign['discount_type'] === 'percentage') {
            $discount = $currentCartTotal * ((float)$campaign['discount_value'] / 100);
            
            // Apply maximum discount cap if set
            if ($campaign['max_discount_amount'] !== null && $discount > $campaign['max_discount_amount']) {
                $discount = (float)$campaign['max_discount_amount'];
            }
        }

        // 10. Prevent negative or zero payout
        $newTotal = $currentCartTotal - $discount;
        if ($newTotal <= 0) {
            // Cap discount to prevent negative total
            $discount = $currentCartTotal;
            $newTotal = 0;
        }

        // 11. Validate discount amount is correct (sanity check)
        if ($discount < 0) {
            return ['success' => false, 'message' => 'Invalid discount amount calculated.'];
        }

        // 12. Apply stacked discounts from existing vouchers
        $totalStackedDiscount = 0;
        foreach ($existingVouchers as $existing) {
            if (isset($existing['discount_amount'])) {
                $totalStackedDiscount += $existing['discount_amount'];
            }
        }

        $finalTotal = max(0, $currentCartTotal - $discount - $totalStackedDiscount);

        return [
            'success' => true,
            'voucher_data' => array_merge($voucher, [
                'campaign_name' => $campaign['name'],
                'label' => $campaign['label'] ?? $campaign['name'],
                'campaign_id' => $campaign['id'],
            ]),
            'discount_amount' => round($discount, 2),
            'new_total' => round($finalTotal, 2),
            'stacked_discount' => round($totalStackedDiscount, 2),
        ];
    }

    /**
     * Record voucher usage after successful order
     * 
     * @param int $userId
     * @param int $voucherCodeId
     * @param int|null $orderId
     * @return bool
     */
    public function recordUsage(int $userId, int $voucherCodeId, ?int $orderId = null): bool
    {
        $voucher = $this->voucherModel->find($voucherCodeId);
        if (!$voucher) {
            return false;
        }

        // Mark unique vouchers as redeemed
        $campaign = $this->campaignModel->find($voucher['campaign_id']);
        if ($campaign && $campaign['code_type'] === 'unique_batch') {
            $this->voucherModel->update($voucherCodeId, [
                'is_redeemed' => 1,
                'redeemed_at' => date('Y-m-d H:i:s'),
                'redeemed_by_user_id' => $userId,
            ]);
        }

        // Log usage for per-user limit tracking
        return $this->usageLogModel->logUsage(
            $userId,
            $voucher['campaign_id'],
            $voucherCodeId,
            $orderId
        ) !== false;
    }
}