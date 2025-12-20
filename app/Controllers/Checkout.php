<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Libraries\MayaService;
use App\Libraries\VoucherEngine;
use App\Libraries\CartService;
use App\Libraries\PointsService;
use App\Libraries\FirstPurchaseService;

class Checkout extends BaseController
{
    protected $mayaService;
    protected $voucherEngine;
    protected $orderModel;
    protected $productModel;
    protected $cartService;
    protected $userModel;
    protected $pointsService;
    protected $firstPurchaseService;

    public function __construct()
    {
        $this->mayaService = new MayaService();
        $this->voucherEngine = new VoucherEngine();
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->cartService = new CartService();
        $this->userModel = new UserModel();
        $this->pointsService = new PointsService();
        $this->firstPurchaseService = new FirstPurchaseService();
    }

    /**
     * Show checkout page/modal data
     */
    public function show()
    {
        $userId = session()->get('id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to checkout.'
            ]);
        }

        $cart = $this->cartService->getCartSummary();
        $user = $this->userModel->find($userId);
        
        // Get points balance (from cached value or calculate)
        $points = $user['current_points_balance'] ?? 0;
        
        // Points to peso conversion (100 points = 1 peso)
        $pointsToPesoRatio = 100;

        // Check for first purchase promo eligibility
        $firstPurchasePromo = null;
        $productIds = $this->cartService->getProductIds();
        $fpResult = $this->firstPurchaseService->calculateDiscount($userId, $cart['subtotal'], $productIds);
        if ($fpResult['applicable']) {
            $firstPurchasePromo = [
                'label' => $fpResult['label'],
                'discount' => $fpResult['discount'],
                'message' => $fpResult['message'],
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'cart' => $cart,
            'points' => $points,
            'points_to_peso_ratio' => $pointsToPesoRatio,
            'user_email' => $user['email'] ?? null,
            'first_purchase_promo' => $firstPurchasePromo,
        ]);
    }

    /**
     * Apply voucher code to cart
     */
    public function applyVoucher()
    {
        $userId = session()->get('id');
        $json = $this->request->getJSON();
        $voucherCode = $json->voucher_code ?? null;

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to apply voucher.'
            ]);
        }

        if (!$voucherCode) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Voucher code is required.'
            ]);
        }

        $cart = $this->cartService->getCartSummary();
        
        if ($cart['item_count'] == 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cart is empty.'
            ]);
        }

        $productIds = $this->cartService->getProductIds();
        
        // Check if voucher is already applied (prevent stacking same voucher)
        $appliedVouchers = session()->get('applied_vouchers') ?? [];
        foreach ($appliedVouchers as $applied) {
            if (strtoupper($applied['code']) === strtoupper($voucherCode)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This voucher is already applied.'
                ]);
            }
        }
        
        $result = $this->voucherEngine->applyVoucher(
            $voucherCode,
            $cart['subtotal'],
            $productIds,
            $userId,
            $appliedVouchers // Pass existing vouchers to check stacking rules
        );

        if ($result['success']) {
            // For non-stackable vouchers, replace any existing. Otherwise add.
            if (!($result['voucher_data']['is_stackable'] ?? false)) {
                // Replace all vouchers with this one
                $appliedVouchers = [];
            }
            
            $appliedVouchers[] = [
                'code' => $voucherCode,
                'voucher_data' => $result['voucher_data'],
                'discount_amount' => $result['discount_amount'],
            ];
            session()->set('applied_vouchers', $appliedVouchers);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Voucher applied successfully!',
                'discount_amount' => $result['discount_amount'],
                'new_total' => $result['new_total'],
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => $result['message'] ?? 'Invalid voucher code.'
        ]);
    }

    /**
     * Apply points redemption
     */
    public function applyPoints()
    {
        $userId = session()->get('id');
        $json = $this->request->getJSON();
        $pointsToRedeem = (int)($json->points ?? 0);

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to redeem points.'
            ]);
        }

        if ($pointsToRedeem <= 0) {
            session()->remove('redeemed_points');
            return $this->response->setJSON([
                'success' => true,
                'points_value' => 0,
                'message' => 'Points redemption cleared.'
            ]);
        }

        // Validate using PointsService (checks non-expired balance)
        $validation = $this->pointsService->validateRedemption($userId, $pointsToRedeem);
        
        if (!$validation['valid']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $validation['message']
            ]);
        }

        // Calculate peso value
        $pesoValue = $this->pointsService->pointsToPesos($pointsToRedeem);

        // Store in session (actual deduction happens at payment success)
        session()->set('redeemed_points', [
            'points' => $pointsToRedeem,
            'peso_value' => $pesoValue,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'points_value' => $pesoValue,
            'message' => 'Points applied successfully!'
        ]);
    }

    public function process()
    {
        $userId = session()->get('id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Please login to checkout.'
            ]);
        }

        // 1. Get Payload
        $json = $this->request->getJSON();
        $email = $json->email ?? null;
        $mobile = $json->mobile ?? null;
        $recipient = $mobile ?? $email; // Mobile number or Email
        $isGift = $json->is_gift ?? false;
        $recipientEmail = $json->recipient_email ?? null;
        $giftMessage = $json->gift_message ?? null;
        $paymentMethod = $json->payment_method ?? 'gcash';
        
        // If gift, use recipient email, otherwise use buyer's contact
        if ($isGift && $recipientEmail) {
            $recipient = $recipientEmail;
        }

        // 2. Get cart
        $cart = $this->cartService->getCartSummary();
        
        if ($cart['item_count'] == 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Cart is empty.'
            ]);
        }

        // 3. Calculate totals with vouchers and points
        $subtotal = $cart['subtotal'];
        $voucherDiscount = 0;
        $pointsValue = 0;
        $appliedVouchers = session()->get('applied_vouchers') ?? [];
        $redeemedPoints = session()->get('redeemed_points');

        // Calculate voucher discounts
        $voucherCodeIds = [];
        foreach ($appliedVouchers as $voucher) {
            $voucherDiscount += $voucher['discount_amount'];
            if (isset($voucher['voucher_data']['id'])) {
                $voucherCodeIds[] = $voucher['voucher_data']['id'];
            }
        }

        // Calculate points value
        if ($redeemedPoints) {
            $pointsValue = $redeemedPoints['peso_value'];
        }

        // Calculate first purchase discount
        $firstPurchaseDiscount = 0;
        $productIds = $this->cartService->getProductIds();
        $fpResult = $this->firstPurchaseService->calculateDiscount($userId, $subtotal, $productIds);
        if ($fpResult['applicable']) {
            $firstPurchaseDiscount = $fpResult['discount'];
        }

        // Calculate grand total (include first purchase discount)
        $totalDiscount = $voucherDiscount + $pointsValue + $firstPurchaseDiscount;
        $grandTotal = max(0, $subtotal - $totalDiscount);

        // 4. Generate UUID for Maya (Idempotency Key)
        $requestId = $this->generateUuidV4();

        // 5. Start database transaction - order only committed on payment success
        $db = \Config\Database::connect();
        $db->transStart();

        // 6. Create Local Order (Status: PENDING)
        $orderData = [
            'user_id' => $userId,
            'subtotal' => $subtotal,
            'discount_total' => $voucherDiscount + $firstPurchaseDiscount,
            'points_redeemed_value' => $pointsValue,
            'grand_total' => $grandTotal,
            'payment_status' => 'pending',
            'fulfillment_status' => 'pending',
            'maya_checkout_id' => $requestId
        ];
        
        // Add gift fields if it's a gift
        if ($isGift) {
            $orderData['recipient_email'] = $recipientEmail;
            $orderData['gift_message'] = $giftMessage;
        }
        
        $orderId = $this->orderModel->insert($orderData);

        if (!$orderId) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to initialize order.']);
        }

        // 7. Create order items with points snapshot
        $orderItemsForPoints = []; // Store for points calculation
        foreach ($cart['items'] as $item) {
            $product = $this->productModel->find($item['product_id']);
            $pointsAtPurchase = (int)($product['points_to_earn'] ?? 0);
            
            $db->table('order_items')->insert([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_at_purchase' => $item['price'],
                'total' => $item['line_total'],
                'points_earned_at_purchase' => $pointsAtPurchase,
            ]);
            
            // Keep track for points calculation
            $orderItemsForPoints[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'points_earned_at_purchase' => $pointsAtPurchase,
            ];
        }

        // 8. Record applied vouchers
        foreach ($voucherCodeIds as $voucherCodeId) {
            $db->table('order_applied_vouchers')->insert([
                'order_id' => $orderId,
                'voucher_code_id' => $voucherCodeId,
                'discount_amount_applied' => $voucherDiscount / count($voucherCodeIds), // Split evenly for now
            ]);
            
            // Record usage in voucher_usage_log
            $this->voucherEngine->recordUsage($userId, $voucherCodeId, $orderId);
        }

        // 8. Process payment - For now, we'll handle multiple products
        // Note: Maya API might need to be called per product or in batch
        // This is a simplified version - you may need to adjust based on Maya API capabilities
        
        // For multiple products, we might need to process them individually
        // or use a batch API if available. For now, let's process the first product
        // as a placeholder - you'll need to adjust this based on your Maya integration
        
        $firstProduct = $this->productModel->find($cart['items'][0]['product_id']);
        
        if (!$firstProduct || empty($firstProduct['maya_product_code'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Product configuration error.'
            ]);
        }
        
        $mayaResponse = $this->mayaService->disburseProduct(
            $requestId, 
            $firstProduct['maya_product_code'], 
            $recipient
        );

        // 7. Handle Maya Response
        if ($mayaResponse['success']) {
            $data = $mayaResponse['data'];
            
            // Scenario A: Instant Success (We got the PIN)
            if (isset($data['code']) && !empty($data['code'])) {
                $this->orderModel->update($orderId, [
                    'payment_status' => 'paid',
                    'fulfillment_status' => 'sent',
                    'maya_reference_number' => $data['transactionId'], // [cite: 177]
                    // Store the PIN code securely. For V1, assume we save it in a secure log or send via email.
                ]);

                // Award points for successful purchase (Pitfall #1, #4 prevention)
                $pointsAwarded = $this->pointsService->awardPointsForOrder($userId, $orderId, $orderItemsForPoints);
                
                // Deduct redeemed points (Pitfall #6 prevention - only after success)
                if ($redeemedPoints && $redeemedPoints['points'] > 0) {
                    $this->pointsService->redeemPointsForOrder($userId, $redeemedPoints['points'], $orderId);
                }

                // Mark first purchase complete (Pitfall #2 prevention - only after success)
                $this->firstPurchaseService->markFirstPurchaseComplete($userId);

                // Clear cart and session data
                $this->cartService->clearCart();
                session()->remove('applied_vouchers');
                session()->remove('redeemed_points');

                // Commit the transaction
                $db->transComplete();

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Order complete!',
                    'pin_code' => $data['code'], // Display to user
                    'points_earned' => $pointsAwarded,
                ]);
            } 
            
            // Scenario B: Async/Pending (Maya is processing)
            //  Status is 'PENDING'
            else {
                $this->orderModel->update($orderId, [
                    'payment_status' => 'paid', // They paid, but no PIN yet
                    'fulfillment_status' => 'processing'
                ]);
                
                // Award points for async success too
                $pointsAwarded = $this->pointsService->awardPointsForOrder($userId, $orderId, $orderItemsForPoints);
                
                // Deduct redeemed points
                if ($redeemedPoints && $redeemedPoints['points'] > 0) {
                    $this->pointsService->redeemPointsForOrder($userId, $redeemedPoints['points'], $orderId);
                }

                // Mark first purchase complete (async success)
                $this->firstPurchaseService->markFirstPurchaseComplete($userId);
                
                // Clear session data
                $this->cartService->clearCart();
                session()->remove('applied_vouchers');
                session()->remove('redeemed_points');

                // Commit the transaction
                $db->transComplete();
                
                return $this->response->setJSON([
                    'status' => 'pending',
                    'message' => 'Your order is being processed. Please wait.',
                    'points_earned' => $pointsAwarded,
                ]);
            }

        } else {
            // Scenario C: Failure (StockUnavailable, etc.)
            // Rollback the transaction - don't create failed orders in DB
            $db->transRollback();

            // Clear session data so user can retry with same vouchers
            session()->remove('applied_vouchers');
            session()->remove('redeemed_points');

            return $this->response->setJSON([
                'status' => 'error', 
                'code' => $mayaResponse['error'],
                'message' => $mayaResponse['message']
            ]);
            }
        }

    // Helper for UUID v4
    private function generateUuidV4() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}