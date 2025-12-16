<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Custom Email Service Configuration
 * 
 * Configuration for the custom email service that uses
 * the external email API endpoint.
 */
class EmailService extends BaseConfig
{
    /**
     * Email API endpoint URL
     */
    public string $apiUrl = 'http://172.31.10.142/email/';

    /**
     * Secret key for generating hash
     */
    public string $secret = '';

    /**
     * Default sender name
     */
    public string $senderName = 'PlayPass';

    /**
     * Request timeout in seconds
     */
    public int $timeout = 5; // Reduced to 5 seconds to prevent long delays

    public function __construct()
    {
        parent::__construct();

        // Load from environment variables if available
        $this->apiUrl = env('EMAIL_API_URL', 'http://172.31.10.142/email/');
        $this->secret = env('EMAIL_SECRET', '');
        $this->senderName = env('EMAIL_SENDER_NAME', 'PlayPass');
        $this->timeout = (int) env('EMAIL_TIMEOUT', 30);
    }
}
