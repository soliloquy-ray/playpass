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
        $this->googleRedirectUri = env('GOOGLE_REDIRECT_URI', base_url('auth/google/callback'));

        $this->facebookAppId = env('FACEBOOK_APP_ID', '');
        $this->facebookAppSecret = env('FACEBOOK_APP_SECRET', '');
        $this->facebookRedirectUri = env('FACEBOOK_REDIRECT_URI', base_url('auth/facebook/callback'));
    }
}

