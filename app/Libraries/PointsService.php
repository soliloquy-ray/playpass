<?php

namespace App\Libraries;

use App\Models\PointLedgerModel;
use App\Models\ProductModel;
use App\Models\UserModel;

/**
 * PointsService - Centralized business logic for the points system.
 * 
 * Handles:
 * - Calculating points to earn from order items
 * - Awarding points after successful payment (not before!)
 * - Redeeming points with proper validation
 * - Preventing expired point usage
 * 
 * Follows the same pattern as VoucherEngine for consistency.
 */
class PointsService
{
    protected PointLedgerModel $ledgerModel;
    protected ProductModel $productModel;
    protected UserModel $userModel;

    // 100 points = 1 peso
    public const POINTS_TO_PESO_RATIO = 100;

    public function __construct()
    {
        $this->ledgerModel = new PointLedgerModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
    }

    /**
     * Calculate total points to earn from an order.
     * 
     * Points are calculated per product × quantity.
     * Uses the snapshot value (points_earned_at_purchase) if available,
     * otherwise fetches current product value.
     * 
     * @param array $orderItems Array of order items with product_id, quantity, and optionally points_earned_at_purchase
     * @return int Total points to earn
     */
    public function calculateOrderPointsEarnings(array $orderItems): int
    {
        $totalPoints = 0;

        foreach ($orderItems as $item) {
            $productId = $item['product_id'] ?? 0;
            $quantity = (int)($item['quantity'] ?? 1);
            
            // Use snapshot if available, otherwise fetch from product
            if (isset($item['points_earned_at_purchase'])) {
                $pointsPerUnit = (int)$item['points_earned_at_purchase'];
            } else {
                $product = $this->productModel->find($productId);
                $pointsPerUnit = (int)($product['points_to_earn'] ?? 0);
            }

            $totalPoints += $pointsPerUnit * $quantity;
        }

        return $totalPoints;
    }

    /**
     * Award points for a completed order.
     * 
     * CRITICAL: Only call this AFTER payment is confirmed successful.
     * This prevents pitfall #4 (payment fails but reward credited).
     * 
     * @param int $userId
     * @param int $orderId
     * @param array $orderItems Items with quantity and points_earned_at_purchase
     * @return int Points awarded (0 if none)
     */
    public function awardPointsForOrder(int $userId, int $orderId, array $orderItems): int
    {
        $pointsToAward = $this->calculateOrderPointsEarnings($orderItems);

        if ($pointsToAward <= 0) {
            return 0;
        }

        $result = $this->ledgerModel->earnPoints(
            $userId,
            $pointsToAward,
            "order_{$orderId}",
            PointLedgerModel::TYPE_PURCHASE_REWARD
        );

        if ($result) {
            // Update cached balance on user record
            $this->ledgerModel->syncUserBalance($userId);
        }

        return $result ? $pointsToAward : 0;
    }

    /**
     * Validate if user can redeem the specified points.
     * 
     * Checks:
     * 1. Points are positive
     * 2. User has sufficient non-expired balance
     * 
     * @param int $userId
     * @param int $pointsToRedeem
     * @return array ['valid' => bool, 'message' => string, 'available' => int]
     */
    public function validateRedemption(int $userId, int $pointsToRedeem): array
    {
        if ($pointsToRedeem <= 0) {
            return [
                'valid' => false,
                'message' => 'Invalid points amount.',
                'available' => 0,
            ];
        }

        $availablePoints = $this->ledgerModel->getBalance($userId);

        if ($availablePoints < $pointsToRedeem) {
            return [
                'valid' => false,
                'message' => "Insufficient points. You have {$availablePoints} points available.",
                'available' => $availablePoints,
            ];
        }

        return [
            'valid' => true,
            'message' => 'Points can be redeemed.',
            'available' => $availablePoints,
        ];
    }

    /**
     * Redeem points for an order.
     * 
     * CRITICAL: Only call this AFTER payment is confirmed successful.
     * This prevents pitfall #6 (points deducted but purchase fails).
     * 
     * Also re-validates at redemption time to prevent pitfall #7
     * (expired points redeemed if purchase initiated before expiration).
     * 
     * @param int $userId
     * @param int $pointsToRedeem
     * @param int $orderId
     * @return array ['success' => bool, 'message' => string, 'peso_value' => float]
     */
    public function redeemPointsForOrder(int $userId, int $pointsToRedeem, int $orderId): array
    {
        // Re-validate at redemption time (pitfall #7)
        $validation = $this->validateRedemption($userId, $pointsToRedeem);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
                'peso_value' => 0,
            ];
        }

        $result = $this->ledgerModel->redeemPoints($userId, $pointsToRedeem, $orderId);

        if ($result) {
            // Update cached balance
            $this->ledgerModel->syncUserBalance($userId);

            return [
                'success' => true,
                'message' => "Redeemed {$pointsToRedeem} points successfully.",
                'peso_value' => $this->pointsToPesos($pointsToRedeem),
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to redeem points. Please try again.',
            'peso_value' => 0,
        ];
    }

    /**
     * Convert points to peso value.
     * 100 points = 1 peso
     */
    public function pointsToPesos(int $points): float
    {
        return round($points / self::POINTS_TO_PESO_RATIO, 2);
    }

    /**
     * Convert peso value to points equivalent.
     * 1 peso = 100 points
     */
    public function pesosToPoints(float $pesos): int
    {
        return (int)floor($pesos * self::POINTS_TO_PESO_RATIO);
    }

    /**
     * Get user's current valid points balance.
     */
    public function getUserBalance(int $userId): int
    {
        return $this->ledgerModel->getBalance($userId);
    }

    /**
     * Get the maximum peso discount user can apply with their points.
     */
    public function getMaxDiscount(int $userId): float
    {
        $balance = $this->getUserBalance($userId);
        return $this->pointsToPesos($balance);
    }

    /**
     * Validate that redemption won't exceed cart total (prevent negative payout).
     * 
     * @param int $points Points to redeem
     * @param float $cartTotal Current cart total after other discounts
     * @return int Adjusted points (capped if needed)
     */
    public function capRedemptionToCart(int $points, float $cartTotal): int
    {
        $maxRedeemablePoints = $this->pesosToPoints($cartTotal);
        return min($points, $maxRedeemablePoints);
    }
}
