<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Libraries\MayaService;
use App\Libraries\VoucherEngine;

class CheckoutController extends BaseController
{
    protected $mayaService;
    protected $voucherEngine;
    protected $orderModel;
    protected $productModel;

    public function __construct()
    {
        $this->mayaService = new MayaService();
        $this->voucherEngine = new VoucherEngine();
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
    }

    public function process()
    {
        // 1. Get Payload (Assuming JSON input from Front-end)
        $json = $this->request->getJSON();
        $userId = $json->user_id; 
        $productId = $json->product_id;
        $recipient = $json->recipient; // Mobile number or Email
        $voucherCode = $json->voucher_code ?? null;

        // 2. Fetch Product
        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Product not found.']);
        }

        // 3. Calculate Totals (Using Voucher Engine)
        $subtotal = $product['price'];
        $discount = 0;

        if ($voucherCode) {
            $voucherResult = $this->voucherEngine->applyVoucher($voucherCode, $subtotal);
            if ($voucherResult['success']) {
                $discount = $voucherResult['discount_amount'];
            }
        }
        
        $grandTotal = max(0, $subtotal - $discount);

        // 4. Generate UUID for Maya (Idempotency Key)
        $requestId = $this->generateUuidV4();

        // 5. Create Local Order (Status: PENDING)
        // We save BEFORE calling API to ensure we have a record even if API crashes
        $orderId = $this->orderModel->insert([
            'user_id' => $userId,
            'subtotal' => $subtotal,
            'discount_total' => $discount,
            'grand_total' => $grandTotal,
            'payment_status' => 'pending',
            'fulfillment_status' => 'pending',
            'maya_checkout_id' => $requestId // Important for tracking!
        ]);

        if (!$orderId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to initialize order.']);
        }

        // 6. Call Maya API (The Purchase)
        // NOTE: In a real app, you would process Payment (Stripe/Paypal) here first.
        // Assuming payment is successful or using Wallet Balance:
        
        $mayaResponse = $this->mayaService->disburseProduct(
            $requestId, 
            $product['maya_product_code'], 
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

        public function processPayment($user)
        {
            // ... (Your validation logic) ...
        $json = $this->request->getJSON();
        $orderId = $json->order_id;
        $finalAmount = $json->amount;
            
            // Prepare Data for Service
            $orderData = [
                'order_id'      => $orderId, // Generated from DB insert
                'price'         => $finalAmount,
                'service'       => 'Playpass Purchase', // Or specific product name
                'quantity'      => 1,
                'first_name'    => $user->first_name,
                'last_name'     => $user->last_name,
                'mobile'        => $user->mobile,
                'email'         => $user->email,
                'address_line1' => 'Digital Delivery', // Since it's e-PINs
                'address_line2' => 'N/A'
            ];

            // Call the Refactored Service
            $result = $this->mayaService->initiateCheckout($orderData);

            if ($result['success']) {
                // Save the checkoutId to DB so we can verify later
                $this->orderModel->update($orderId, ['maya_checkout_id' => $result['checkoutId']]);
                
                // Go to Maya
                return redirect()->to($result['redirectUrl']);
            } else {
                return redirect()->back()->with('error', $result['message']);
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