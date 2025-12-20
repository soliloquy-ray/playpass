<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Libraries\MayaService;
use App\Libraries\VoucherEngine;
use App\Libraries\CartService;

class Checkout extends BaseController
{
    protected $mayaService;
    protected $voucherEngine;
    protected $orderModel;
    protected $productModel;
    protected $cartService;
    protected $userModel;

    public function __construct()
    {
        $this->mayaService = new MayaService();
        $this->voucherEngine = new VoucherEngine();
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->cartService = new CartService();
        $this->userModel = new UserModel();
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

        return $this->response->setJSON([
            'success' => true,
            'cart' => $cart,
            'points' => $points,
            'points_to_peso_ratio' => $pointsToPesoRatio,
            'user_email' => $user['email'] ?? null,
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
        
        $result = $this->voucherEngine->applyVoucher(
            $voucherCode,
            $cart['subtotal'],
            $productIds,
            $userId,
            [] // Existing vouchers
        );

        if ($result['success']) {
            // Store applied voucher in session
            $appliedVouchers = session()->get('applied_vouchers') ?? [];
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

        $user = $this->userModel->find($userId);
        $availablePoints = $user['current_points_balance'] ?? 0;

        if ($pointsToRedeem <= 0) {
            session()->remove('redeemed_points');
            return $this->response->setJSON([
                'success' => true,
                'points_value' => 0,
                'message' => 'Points redemption cleared.'
            ]);
        }

        if ($pointsToRedeem > $availablePoints) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Insufficient points. You have ' . $availablePoints . ' points available.'
            ]);
        }

        // Points to peso conversion (100 points = 1 peso)
        $pointsToPesoRatio = 100;
        $pesoValue = round($pointsToRedeem / $pointsToPesoRatio, 2);

        // Store in session
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

        // Calculate grand total
        $grandTotal = max(0, $subtotal - $voucherDiscount - $pointsValue);

        // 4. Generate UUID for Maya (Idempotency Key)
        $requestId = $this->generateUuidV4();

        // 5. Create Local Order (Status: PENDING)
        $orderData = [
            'user_id' => $userId,
            'subtotal' => $subtotal,
            'discount_total' => $voucherDiscount,
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
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to initialize order.']);
        }

        // 6. Create order items
        $db = \Config\Database::connect();
        foreach ($cart['items'] as $item) {
            $db->table('order_items')->insert([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_at_purchase' => $item['price'],
                'total' => $item['line_total'],
            ]);
        }

        // 7. Record applied vouchers
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

                // Clear cart and session data
                $this->cartService->clearCart();
                session()->remove('applied_vouchers');
                session()->remove('redeemed_points');

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Order complete!',
                    'pin_code' => $data['code'] // Display to user
                ]);
            } 
            
            // Scenario B: Async/Pending (Maya is processing)
            //  Status is 'PENDING'
            else {
                $this->orderModel->update($orderId, [
                    'payment_status' => 'paid', // They paid, but no PIN yet
                    'fulfillment_status' => 'processing'
                ]);
                return $this->response->setJSON([
                    'status' => 'pending',
                    'message' => 'Your order is being processed. Please wait.'
                ]);
            }

        } else {
            // Scenario C: Failure (StockUnavailable, etc.)
            // [cite: 188] Error codes like 'StockUnavailable' or 'Insufficient Funds'
            $this->orderModel->update($orderId, [
                'payment_status' => 'failed',
                'fulfillment_status' => 'failed'
            ]);

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