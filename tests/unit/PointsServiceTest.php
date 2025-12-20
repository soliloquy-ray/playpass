<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Libraries\PointsService;
use App\Models\PointLedgerModel;

/**
 * Unit tests for the Points System
 * 
 * Run: php vendor/bin/phpunit tests/unit/PointsServiceTest.php
 */
class PointsServiceTest extends CIUnitTestCase
{
    protected PointsService $pointsService;
    protected PointLedgerModel $ledgerModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pointsService = new PointsService();
        $this->ledgerModel = new PointLedgerModel();
    }

    /**
     * Test points to peso conversion (100 points = 1 peso)
     */
    public function testPointsToPesos(): void
    {
        $this->assertEquals(1.0, $this->pointsService->pointsToPesos(100));
        $this->assertEquals(0.5, $this->pointsService->pointsToPesos(50));
        $this->assertEquals(10.0, $this->pointsService->pointsToPesos(1000));
        $this->assertEquals(0.0, $this->pointsService->pointsToPesos(0));
    }

    /**
     * Test peso to points conversion (1 peso = 100 points)
     */
    public function testPesosToPoints(): void
    {
        $this->assertEquals(100, $this->pointsService->pesosToPoints(1.0));
        $this->assertEquals(50, $this->pointsService->pesosToPoints(0.5));
        $this->assertEquals(1000, $this->pointsService->pesosToPoints(10.0));
        // Floor behavior - 1.99 pesos = 199 points (not 200)
        $this->assertEquals(199, $this->pointsService->pesosToPoints(1.99));
    }

    /**
     * Test points calculation from order items
     */
    public function testCalculateOrderPointsEarnings(): void
    {
        $orderItems = [
            ['product_id' => 1, 'quantity' => 2, 'points_earned_at_purchase' => 50],
            ['product_id' => 2, 'quantity' => 1, 'points_earned_at_purchase' => 100],
            ['product_id' => 3, 'quantity' => 3, 'points_earned_at_purchase' => 25],
        ];

        // Expected: (2*50) + (1*100) + (3*25) = 100 + 100 + 75 = 275
        $total = $this->pointsService->calculateOrderPointsEarnings($orderItems);
        $this->assertEquals(275, $total);
    }

    /**
     * Test order with zero points products
     */
    public function testCalculateOrderPointsWithZeroPointsProducts(): void
    {
        $orderItems = [
            ['product_id' => 1, 'quantity' => 2, 'points_earned_at_purchase' => 0],
            ['product_id' => 2, 'quantity' => 1, 'points_earned_at_purchase' => 50],
        ];

        // Expected: (2*0) + (1*50) = 50
        $total = $this->pointsService->calculateOrderPointsEarnings($orderItems);
        $this->assertEquals(50, $total);
    }

    /**
     * Test empty order returns zero points
     */
    public function testCalculateOrderPointsEmptyOrder(): void
    {
        $orderItems = [];
        $total = $this->pointsService->calculateOrderPointsEarnings($orderItems);
        $this->assertEquals(0, $total);
    }

    /**
     * Test redemption validation with zero points requested
     */
    public function testValidateRedemptionZeroPoints(): void
    {
        $result = $this->pointsService->validateRedemption(1, 0);
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('Invalid', $result['message']);
    }

    /**
     * Test redemption validation with negative points
     */
    public function testValidateRedemptionNegativePoints(): void
    {
        $result = $this->pointsService->validateRedemption(1, -100);
        $this->assertFalse($result['valid']);
    }

    /**
     * Test capping redemption to cart total (prevent negative payout)
     */
    public function testCapRedemptionToCart(): void
    {
        // User wants to redeem 1000 points (10 pesos) but cart is only 5 pesos
        $cartTotal = 5.0;
        $pointsRequested = 1000;
        
        // Should cap to 500 points (5 pesos worth)
        $cappedPoints = $this->pointsService->capRedemptionToCart($pointsRequested, $cartTotal);
        $this->assertEquals(500, $cappedPoints);
    }

    /**
     * Test cap doesn't affect smaller redemptions
     */
    public function testCapRedemptionNoCapNeeded(): void
    {
        // User wants to redeem 200 points (2 pesos) and cart is 10 pesos
        $cartTotal = 10.0;
        $pointsRequested = 200;
        
        // Should return original 200 points
        $cappedPoints = $this->pointsService->capRedemptionToCart($pointsRequested, $cartTotal);
        $this->assertEquals(200, $cappedPoints);
    }

    /**
     * Test PointLedgerModel constants
     */
    public function testTransactionTypeConstants(): void
    {
        $this->assertEquals('purchase_reward', PointLedgerModel::TYPE_PURCHASE_REWARD);
        $this->assertEquals('redemption', PointLedgerModel::TYPE_REDEMPTION);
        $this->assertEquals('referral_bonus', PointLedgerModel::TYPE_REFERRAL_BONUS);
        $this->assertEquals('adjustment', PointLedgerModel::TYPE_ADJUSTMENT);
    }

    /**
     * Test PointsService ratio constant
     */
    public function testPointsTopesoRatioConstant(): void
    {
        $this->assertEquals(100, PointsService::POINTS_TO_PESO_RATIO);
    }
}
