<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

/**
 * OAuth Configuration
 * 
 * Store your OAuth credentials here. For production, it's recommended
 * to use environment variables instead of hardcoding values.
 */
class OAuth extends BaseConfig
{
    /**
     * Google OAuth Configuration
     */
    public string $googleClientId = '';
    public string $googleClientSecret = '';
    public string $googleRedirectUri = '';

    /**
     * Facebook OAuth Configuration
     */
    public string $facebookAppId = '';
    public string $facebookAppSecret = '';
    public string $facebookRedirectUri = '';

    public function __construct()
    {
        parent::__construct();

        // Load from environment variables if available
        $this->googleClientId = env('GOOGLE_CLIENT_ID', '');
        $this->googleClientSecret = env('GOOGLE_CLIENT_SECRET', '');
        
        // For redirect URI, handle .test domains (not accepted by Google OAuth)
        // Use localhost if domain ends with .test, otherwise use base_url()
        if (env('GOOGLE_REDIRECT_URI')) {
            // Explicit override from .env
            $this->googleRedirectUri = env('GOOGLE_REDIRECT_URI');
        } else {
            $baseUrl = base_url('app/auth/google/callback');
            $host = parse_url($baseUrl, PHP_URL_HOST) ?? '';
            
            // Google OAuth doesn't accept .test domains, so convert to localhost
            if (preg_match('/\.test$/', $host)) {
                $this->googleRedirectUri = str_replace($host, 'localhost', $baseUrl);
            } else {
                $this->googleRedirectUri = $baseUrl;
            }
        }

        $this->facebookAppId = env('FACEBOOK_APP_ID', '');
        $this->facebookAppSecret = env('FACEBOOK_APP_SECRET', '');
        
        // Same handling for Facebook
        if (env('FACEBOOK_REDIRECT_URI')) {
            // Explicit override from .env
            $this->facebookRedirectUri = env('FACEBOOK_REDIRECT_URI');
        } else {
            $facebookBaseUrl = base_url('app/auth/facebook/callback');
            $host = parse_url($facebookBaseUrl, PHP_URL_HOST) ?? '';
            
            // Facebook OAuth doesn't accept .test domains, so convert to localhost
            if (preg_match('/\.test$/', $host)) {
                $this->facebookRedirectUri = str_replace($host, 'localhost', $facebookBaseUrl);
            } else {
                $this->facebookRedirectUri = $facebookBaseUrl;
            }
        }
    }
}

