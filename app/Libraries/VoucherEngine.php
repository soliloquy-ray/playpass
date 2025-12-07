<?php

namespace App\Libraries;

use App\Models\VoucherCodeModel;

class VoucherEngine
{
    protected $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherCodeModel();
    }

    /**
     * The Brain: Decides if a code can be applied to the current cart
     */
    public function applyVoucher(string $code, float $currentCartTotal, array $existingVouchers = [])
    {
        // 1. Fetch Voucher
        $voucher = $this->voucherModel->getValidVoucherByCode($code);
        
        if (!$voucher) {
            return ['success' => false, 'message' => 'Invalid or expired code.'];
        }

        // 2. Check Min Spend (Rule from CSV)
        if ($currentCartTotal < $voucher['min_spend_amount']) {
            return ['success' => false, 'message' => 'Minimum spend of ' . $voucher['min_spend_amount'] . ' required.'];
        }

        // 3. Check Stacking (The Complex Rule)
        // If the NEW voucher is not stackable, and we already have vouchers... fail.
        if ($voucher['is_stackable'] == 0 && count($existingVouchers) > 0) {
            return ['success' => false, 'message' => 'This voucher cannot be used with other promotions.'];
        }

        // Also check if any EXISTING voucher forbids stacking
        foreach ($existingVouchers as $applied) {
            if ($applied['is_stackable'] == 0) {
                return ['success' => false, 'message' => 'An exclusive voucher is already applied.'];
            }
        }

        // 4. Calculate Discount
        $discount = 0;
        if ($voucher['discount_type'] == 'fixed_amount') {
            $discount = $voucher['discount_value'];
        } elseif ($voucher['discount_type'] == 'percentage') {
            $discount = $currentCartTotal * ($voucher['discount_value'] / 100);
            // TODO: Apply "Max Discount Cap" logic here if column exists
        }

        return [
            'success' => true,
            'voucher_data' => $voucher,
            'discount_amount' => $discount,
            'new_total' => max(0, $currentCartTotal - $discount)
        ];
    }
}