<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Google Tag Manager Configuration
 * 
 * Configure your GTM container ID here. Set via environment variable
 * or leave empty to disable GTM.
 */
class GoogleTagManager extends BaseConfig
{
    /**
     * Google Tag Manager Container ID (e.g., GTM-XXXXXXX)
     * Leave empty to disable GTM
     */
    public string $containerId = '';

    public function __construct()
    {
        parent::__construct();

        // Load from environment variable if available
        $this->containerId = env('GTM_CONTAINER_ID', '');
    }

    /**
     * Check if GTM is enabled
     */
    public function isEnabled(): bool
    {
        return !empty($this->containerId);
    }
}
