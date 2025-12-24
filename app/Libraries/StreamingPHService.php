<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;

class StreamingPHService
{
    protected $client;
    protected $baseUrl;
    protected $ident;
    protected $privateKey;
    protected $isMockMode = false;

    public function __construct()
    {
        $this->client = Services::curlrequest();
        
        $this->ident = getenv('STREAMINGPH_IDENT');
        $this->privateKey = getenv('STREAMINGPH_PRIVATE_KEY');
        $this->baseUrl = getenv('STREAMINGPH_BASE_URL') ?: 'https://streamingph.com/Storeapi';
        
        // Check for mock mode (string 'true' or boolean true)
        $mockEnv = getenv('STREAMINGPH_MOCK_MODE');
        $this->isMockMode = ($mockEnv === 'true' || $mockEnv === true);
    }

    /**
     * Get list of all available products (SKUs)
     * Endpoint: /displayProduct
     */
    public function getProducts()
    {
        return $this->sendRequest('displayProduct');
    }

    /**
     * Issue a PIN for a product
     * Endpoint: /issuePIN
     * 
     * @param int|string $productId
     * @param string $mobileNumber 10 or 11 digit mobile number
     */
    public function purchaseProduct($productId, $mobileNumber)
    {
        $params = [
            'productID' => $productId,
            'mobile' => $mobileNumber
        ];

        return $this->sendRequest('issuePIN', $params);
    }

    /**
     * Check wallet balance
     * Endpoint: /checkBalance
     * Note: Documentation indicates action string is "issuePIN" for hash generation 
     * but we should assume it might be "checkBalance" or similar.
     * However, the doc extracted page 12 says:
     * hash = ... + "issuePIN"
     * for the /checkBalance endpoint. This is likely a copy-paste error in their doc or a quirk.
     * We will try to follow the doc literally first.
     */
    public function checkBalance()
    {
        // Based on doc Page 11/12, the hash string uses "issuePIN" as the action suffix
        // even though the endpoint is /checkBalance.
        return $this->sendRequest('checkBalance', [], 'issuePIN');
    }

    /**
     * Generic method to send requests
     * 
     * @param string $endpoint The API endpoint (e.g. 'displayProduct')
     * @param array $extraParams Additional parameters for the body
     * @param string|null $customActionOverride If the hash action string differs from endpoint name
     */
    protected function sendRequest(string $endpoint, array $extraParams = [], string $customActionOverride = null)
    {
        // === MOCK MODE CHECK ===
        if ($this->isMockMode) {
            log_message('info', "[STREAMINGPH MOCK] Request to {$endpoint}: " . json_encode($extraParams));
            return $this->generateMockResponse($endpoint, $extraParams);
        }

        $url = rtrim($this->baseUrl, '/') . '/' . $endpoint;
        $timestamp = time();
        
        // Action string for hash usually matches the endpoint name
        $action = $customActionOverride ?? $endpoint;

        // Hash Generation: first 16 chars of SHA512("MEGA" + privateKey + "StreamPH" + time + action)
        $stringToHash = "MEGA" . $this->privateKey . "StreamPH" . $timestamp . $action;
        $hashData = hash('sha512', $stringToHash);
        $hash = substr($hashData, 0, 16);

        // Prepare Common Payload
        $payload = [
            'ident' => $this->ident,
            'time' => (string)$timestamp,
            'hash' => $hash
        ];

        // Merge extra params
        $payload = array_merge($payload, $extraParams);

        try {
            $response = $this->client->post($url, [
                'multipart' => $payload, // "sent through the BODY via FORM DATA"
                'http_errors' => false
            ]);

            $body = $response->getBody();
            $result = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', "StreamingPH JSON Error: " . json_last_error_msg() . " Body: " . $body);
                return [
                    'status' => 'fail',
                    'value' => 'Invalid JSON response from provider'
                ];
            }

            return $result;

        } catch (\Exception $e) {
            log_message('error', "StreamingPH Exception: " . $e->getMessage());
            return [
                'status' => 'fail',
                'value' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate simulated responses for testing
     */
    protected function generateMockResponse(string $endpoint, array $params)
    {
        $tid = rand(1000, 9999);

        switch ($endpoint) {
            case 'displayProduct':
                return [
                    'status' => 'success',
                    'value' => [
                        [
                            'name' => 'VIU Premium 3 Days',
                            'type' => 'VIU 3 Days',
                            'amount' => '29',
                            'validity' => '3',
                            'description' => 'Mock Description',
                            'productID' => 'MOCK-VIU-3',
                            'balance' => 'NOLIMIT'
                        ],
                        [
                            'name' => 'VIU Premium 30 Days',
                            'type' => 'VIU 30 Days',
                            'amount' => '99',
                            'validity' => '30',
                            'description' => 'Mock 30 Days Description',
                            'productID' => 'MOCK-VIU-30',
                            'balance' => '50'
                        ]
                    ],
                    'tid' => $tid
                ];

            case 'issuePIN':
                // Check if we want to simulate failure based on productID
                $prodId = $params['productID'] ?? '';
                if ($prodId === 'FAIL') {
                    return [
                        'status' => 'fail',
                        'value' => 'insufficient stock',
                        'tid' => $tid
                    ];
                }

                return [
                    'status' => 'success',
                    'value' => [
                        'refCode' => 'MOCK-REF-' . time(),
                        'pincode' => 'MOCK-PIN-' . strtoupper(substr(md5(time()), 0, 6)),
                        'type' => 'Mock Product Type',
                        'amount' => '100',
                        'validity' => '30',
                        'description' => 'This is a mock purchase.',
                        'instruction' => 'Redeem at mock.example.com',
                        'balance' => 'NOLIMIT'
                    ],
                    'tid' => $tid
                ];

            case 'checkBalance':
                return [
                    'status' => 'success',
                    'value' => [
                        'balance' => 50000 // Mock 50k balance
                    ],
                    'tid' => $tid
                ];

            default:
                return [
                    'status' => 'fail',
                    'value' => 'Unknown mock endpoint',
                    'tid' => $tid
                ];
        }
    }
}
