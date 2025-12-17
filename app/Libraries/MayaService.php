<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;

class MayaService
{
    protected $client;
    protected $baseUrl;
    protected $clientId;
    protected $secretKey;
    protected $public_key;

    public function __construct()
    {
        $this->client = Services::curlrequest();
        // Load credentials from .env
        $this->baseUrl = getenv('MAYA_BASE_URL'); // e.g. https://kyuubi-external-api-staging.voyagerapis.com
        $this->clientId = getenv('MAYA_CLIENT_ID');
        $this->secretKey = getenv('MAYA_SECRET_KEY');
        $this->public_key = getenv('MAYA_PUBLIC_KEY');
    }
    
    
    public function initiateCheckout(array $orderData)
        {
            $endpoint = $this->baseUrl . '/checkout/v1/checkouts'; // Standard Checkout Endpoint
            
            // 1. Secure Reference ID (UUID or Timestamp + OrderID)
            $referenceNumber = 'ORD-' . $orderData['order_id'] . '-' . time();

            // 2. Build Payload Array (Safe from JSON injection)
            $payload = [
                'totalAmount' => [
                    'value'    => (float)$orderData['price'],
                    'currency' => 'PHP'
                ],
                'buyer' => [
                    'firstName' => $orderData['first_name'],
                    'lastName'  => $orderData['last_name'],
                    'contact'   => [
                        'phone' => $orderData['mobile'],
                        'email' => $orderData['email']
                    ],
                    'billingAddress' => [
                        'line1'       => $orderData['address_line1'], // cleaned up logic
                        'line2'       => $orderData['address_line2'],
                        'countryCode' => 'PH'
                    ]
                ],
                'requestReferenceNumber' => $referenceNumber,
                'items' => [
                    [
                        'name'        => $orderData['service'], // e.g., "Gamer Bundle"
                        'quantity'    => $orderData['quantity'],
                        'totalAmount' => [
                            'value' => (float)$orderData['price']
                        ]
                    ]
                ],
                'redirectUrl' => [
                    'success' => base_url("checkout/success?order_id={$orderData['order_id']}"),
                    'failure' => base_url("checkout/failure?order_id={$orderData['order_id']}"),
                    'cancel'  => base_url("checkout/cancel?order_id={$orderData['order_id']}")
                ]
            ];

            // 3. Send Request (Using CI4 CURLRequest, no need for Guzzle)
            // Basic Auth: Base64 encoded public key
            $auth = base64_encode($this->public_key . ':'); // Colon usually required for Basic Auth

            try {
                $response = $this->client->post($endpoint, [
                    'headers' => [
                        'Authorization' => 'Basic ' . $auth,
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                    ],
                    'json' => $payload, // CI4 handles json_encode automatically here
                    'http_errors' => false // Prevent crashing on 4xx/5xx
                ]);

                $body = json_decode($response->getBody(), true);

                if ($response->getStatusCode() === 200 && isset($body['redirectUrl'])) {
                    // Success! Return the redirect URL and checkout ID
                    return [
                        'success'      => true,
                        'redirectUrl'  => $body['redirectUrl'],
                        'checkoutId'   => $body['checkoutId']
                    ];
                }

                // API Error Handling
                return [
                    'success' => false,
                    'message' => $body['message'] ?? 'Unknown Maya Error',
                    'code'    => $body['code'] ?? $response->getStatusCode()
                ];

            } catch (\Exception $e) {
                log_message('error', 'Maya Gateway Error: ' . $e->getMessage());
                return ['success' => false, 'message' => 'Payment gateway connection failed.'];
            }
        }
    /**
     * Send a product (e-PIN) to a customer
     * Reference: Kyuubi Guide Section 2.1 [cite: 160]
     */
    public function disburseProduct(string $requestId, string $productCode, string $recipient)
    {
        $endpoint = $this->baseUrl . '/v2/transactions';
        
        // Basic Auth: Base64 encoded username:password [cite: 133]
        $auth = base64_encode($this->clientId . ':' . $this->secretKey);

        try {
            $response = $this->client->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Basic ' . $auth,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => [
                    'requestId'   => $requestId,   // Must be UUIDv4 [cite: 166]
                    'productCode' => $productCode, // Your internal mapping to Maya code
                    'recipient'   => $recipient    // Customer mobile/email
                ],
                'http_errors' => false // We want to handle 4xx/5xx manually
            ]);

            return $this->parseResponse($response);

        } catch (\Exception $e) {
            log_message('error', 'Maya API Connection Error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Connection_Error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Check status of a pending transaction
     * Reference: Kyuubi Guide Section 2.2 [cite: 299]
     */
    public function checkTransactionStatus(string $requestId)
    {
        $endpoint = $this->baseUrl . '/v2/transactions/status?requestId=' . $requestId;
        $auth = base64_encode($this->clientId . ':' . $this->secretKey);

        try {
            $response = $this->client->get($endpoint, [
                'headers' => [
                    'Authorization' => 'Basic ' . $auth,
                    'Accept'        => 'application/json',
                ],
                'http_errors' => false
            ]);
            
            return $this->parseResponse($response);

        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Connection_Error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Legacy Payment Gateway Method
     * 
     * Handles payment gateway requests with support for different payment types
     * (ocular, manual, default) and installment payments.
     * 
     * @param array $order Order data containing: service, quantity, price, first_name, 
     *                    last_name, email, mobile, payment_index, unit, building, 
     *                    property_name, installment_amount (optional)
     * @param string $type Payment type: 'ocular', 'manual', or 'default'
     * @param string $orderId Order ID for building redirect URLs
     * @return array Returns ['success' => bool, 'redirectUrl' => string, 'checkoutId' => string] on success
     */
    public function gateway(array $order, string $type, string $orderId)
    {
        $service  = $order['service'];
        $quantity = $order['quantity'];
        $price    = $order['price'];
        $f_name   = $order['first_name'];
        $l_name   = $order['last_name'];
        $email    = $order['email'];
        $mobile   = $order['mobile'];
        $index    = $order['payment_index'] ?? null;

        // Build redirect URLs based on payment type
        if ($type == 'ocular') {
            $cancel  = base_url() . 'payment_method/installment?order_id=' . $orderId;
            $success = base_url() . 'order/payment_success_ocular?order_id=' . $orderId;
            $failed  = base_url() . 'order/payment_failed?order_id=' . $orderId . '&type=ocular';

            if ($index !== null && !empty($order['installment_amount'] ?? '')) {
                $installmentAmounts = explode(',', $order['installment_amount']);
                if (isset($installmentAmounts[$index])) {
                    $price = $installmentAmounts[$index];
                }
                $success = base_url() . 'order/payment_success_ocular?order_id=' . $orderId . '&payment_index=' . $index;
                $failed  = base_url() . 'order/payment_failed?order_id=' . $orderId . '&type=ocular&payment_index=' . $index;
            }
        } elseif ($type == 'manual') {
            $cancel  = base_url() . 'payment_method/installment?order_id=' . $orderId;
            $success = base_url() . 'order/payment_success_manual?order_id=' . $orderId;
            $failed  = base_url() . 'order/payment_failed?order_id=' . $orderId . '&type=manual';

            if ($index !== null && !empty($order['installment_amount'] ?? '')) {
                $installmentAmounts = explode(',', $order['installment_amount']);
                if (isset($installmentAmounts[$index])) {
                    $price = $installmentAmounts[$index];
                }
                $success = base_url() . 'order/payment_success_manual?order_id=' . $orderId . '&payment_index=' . $index;
                $failed  = base_url() . 'order/payment_failed?order_id=' . $orderId . '&type=manual&payment_index=' . $index;
            }
        } else {
            $cancel  = base_url() . 'payment_method?order_id=' . $orderId;
            $success = base_url() . 'order/payment_success?order_id=' . $orderId;
            $failed  = base_url() . 'order/payment_failed?order_id=' . $orderId . '&type=default';
        }

        // Generate request reference number (timestamp-based)
        $requestReferenceNumber = substr(strtotime(date('Y-m-d H:i:s')), 0, 10);

        // Build address lines
        $line_1 = ($order['unit'] ?? '') . ' ' . ($order['building'] ?? '');
        $line_2 = $order['property_name'] ?? '';

        // Prepare payload
        $payload = [
            'totalAmount' => [
                'value' => (float)$price,
                'currency' => 'PHP'
            ],
            'buyer' => [
                'contact' => [
                    'phone' => $mobile,
                    'email' => $email
                ],
                'billingAddress' => [
                    'line1' => trim($line_1),
                    'line2' => $line_2,
                    'countryCode' => 'PH'
                ],
                'firstName' => $f_name,
                'lastName' => $l_name
            ],
            'requestReferenceNumber' => $requestReferenceNumber,
            'items' => [
                [
                    'amount' => [
                        'value' => (float)$price
                    ],
                    'totalAmount' => [
                        'value' => (float)$price
                    ],
                    'name' => $service,
                    'quantity' => (string)$quantity
                ]
            ],
            'redirectUrl' => [
                'success' => $success,
                'failure' => $failed,
                'cancel' => $cancel
            ]
        ];

        // Get Maya checkout endpoint (assuming it's the same as initiateCheckout)
        $endpoint = $this->baseUrl . '/checkout/v1/checkouts';
        
        // Basic Auth: Base64 encoded public key
        $public_key_encoded = base64_encode($this->public_key);

        try {
            $response = $this->client->post($endpoint, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => 'Basic ' . $public_key_encoded,
                    'content-type' => 'application/json',
                ],
                'json' => $payload,
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200 && isset($result['checkoutId']) && isset($result['redirectUrl'])) {
                return [
                    'success' => true,
                    'redirectUrl' => $result['redirectUrl'],
                    'checkoutId' => $result['checkoutId']
                ];
            }

            // API Error Handling
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Unknown Maya Error',
                'code' => $result['code'] ?? $response->getStatusCode()
            ];

        } catch (\Exception $e) {
            log_message('error', 'Maya Gateway Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment gateway connection failed.'
            ];
        }
    }

    private function parseResponse($response)
    {
        $body = json_decode($response->getBody(), true);
        $statusCode = $response->getStatusCode();

        // Kyuubi returns 200 OK even for errors, but with an "error" object in body [cite: 135]
        if (isset($body['error'])) {
            return [
                'success' => false,
                'error'   => $body['error']['code'] ?? 'Unknown',
                'message' => $body['error']['message'] ?? 'Unknown Error'
            ];
        }

        if (isset($body['data'])) {
            return [
                'success' => true,
                'data'    => $body['data']
            ];
        }

        return ['success' => false, 'error' => 'Invalid_Response', 'message' => 'Empty response from provider'];
    }
}