<?php

namespace App\Libraries;

use App\Models\FirstPurchasePromoModel;
use App\Models\UserModel;
use App\Models\OrderModel;

/**
 * FirstPurchaseService
 * 
 * Handles first purchase promo logic:
 * - Eligibility checking (user hasn't completed first purchase)
 * - Discount calculation with product/minimum spend validation
 * - Tagging user AFTER successful payment only
 * 
 * Pitfall Prevention:
 * - #1: Checks has_completed_first_purchase before applying
 * - #2: Only sets flag after payment success
 * - #3: Caps discount to prevent negative payout
 */
class FirstPurchaseService
{
    protected FirstPurchasePromoModel $promoModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->promoModel = new FirstPurchasePromoModel();
        $this->userModel = new UserModel();
    }

    /**
     * Check if user is eligible for first purchase discount.
     * 
     * @param int $userId
     * @return array ['eligible' => bool, 'promo' => ?array, 'reason' => string]
     */
    public function isEligible(int $userId): array
    {
        // Get user
        $user = $this->userModel->find($userId);
        if (!$user) {
            return [
                'eligible' => false,
                'promo' => null,
                'reason' => 'User not found.',
            ];
        }

        // Check if already completed first purchase
        if (!empty($user['has_completed_first_purchase'])) {
            return [
                'eligible' => false,
                'promo' => null,
                'reason' => 'First purchase already completed.',
            ];
        }

        // Get active promo
        $promo = $this->promoModel->getActivePromo();
        if (!$promo) {
            return [
                'eligible' => false,
                'promo' => null,
                'reason' => 'No active first purchase promo.',
            ];
        }

        return [
            'eligible' => true,
            'promo' => $promo,
            'reason' => 'User is eligible for first purchase discount.',
        ];
    }

    /**
     * Calculate the first purchase discount for an order.
     * 
     * @param int $userId
     * @param float $cartTotal Cart subtotal
     * @param array $productIds Product IDs in cart
     * @return array ['applicable' => bool, 'discount' => float, 'label' => string, 'message' => string]
     */
    public function calculateDiscount(int $userId, float $cartTotal, array $productIds = []): array
    {
        // Check eligibility
        $eligibility = $this->isEligible($userId);
        if (!$eligibility['eligible']) {
            return [
                'applicable' => false,
                'discount' => 0,
                'label' => '',
                'message' => $eligibility['reason'],
            ];
        }

        $promo = $eligibility['promo'];

        // Check minimum spend
        if ($cartTotal < $promo['min_spend_amount']) {
            return [
                'applicable' => false,
                'discount' => 0,
                'label' => $promo['label'] ?? $promo['name'],
                'message' => 'Minimum spend of ₱' . number_format($promo['min_spend_amount'], 2) . ' required.',
            ];
        }

        // Check product applicability
        if (!empty($productIds) && !$this->promoModel->appliesToProducts($promo['id'], $productIds)) {
            return [
                'applicable' => false,
                'discount' => 0,
                'label' => $promo['label'] ?? $promo['name'],
                'message' => 'Promo does not apply to products in cart.',
            ];
        }

        // Calculate discount
        $discount = 0;
        if ($promo['discount_type'] === 'fixed_amount') {
            $discount = (float)$promo['discount_value'];
        } elseif ($promo['discount_type'] === 'percentage') {
            $discount = $cartTotal * ((float)$promo['discount_value'] / 100);

            // Apply max discount cap
            if ($promo['max_discount_amount'] !== null && $discount > $promo['max_discount_amount']) {
                $discount = (float)$promo['max_discount_amount'];
            }
        }

        // Prevent negative payout (Pitfall #3)
        if ($discount >= $cartTotal) {
            $discount = $cartTotal; // Cap at cart total
        }

        return [
            'applicable' => true,
            'discount' => round($discount, 2),
            'label' => $promo['label'] ?? $promo['name'],
            'promo_id' => $promo['id'],
            'message' => 'First purchase discount applied!',
        ];
    }

    /**
     * Mark user as having completed first purchase.
     * 
     * CRITICAL: Only call this AFTER successful payment!
     * This prevents Pitfall #2 (tagged but purchase didn't complete).
     * 
     * @param int $userId
     * @return bool
     */
    public function markFirstPurchaseComplete(int $userId): bool
    {
        return $this->userModel->update($userId, [
            'has_completed_first_purchase' => 1,
        ]);
    }

    /**
     * Check if user has already completed their first purchase.
     */
    public function hasCompletedFirstPurchase(int $userId): bool
    {
        $user = $this->userModel->find($userId);
        return !empty($user['has_completed_first_purchase']);
    }

    /**
     * Get the current active promo details for display.
     */
    public function getActivePromoDetails(): ?array
    {
        $promo = $this->promoModel->getActivePromo();
        if (!$promo) {
            return null;
        }

        // Format discount display
        if ($promo['discount_type'] === 'percentage') {
            $discountDisplay = $promo['discount_value'] . '% OFF';
        } else {
            $discountDisplay = '₱' . number_format($promo['discount_value'], 2) . ' OFF';
        }

        return [
            'id' => $promo['id'],
            'name' => $promo['name'],
            'label' => $promo['label'] ?? $promo['name'],
            'discount_display' => $discountDisplay,
            'min_spend' => $promo['min_spend_amount'],
            'min_spend_display' => $promo['min_spend_amount'] > 0
                ? 'Min. spend: ₱' . number_format($promo['min_spend_amount'], 2)
                : 'No minimum spend',
        ];
    }
}
