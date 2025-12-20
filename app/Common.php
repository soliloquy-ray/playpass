<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('asset_url')) {
    /**
     * Returns the full URL for an asset, handling both relative and absolute URLs.
     * 
     * If the path is already an absolute URL (starts with http:// or https://),
     * it returns it as-is. Otherwise, it prepends base_url() to make it absolute.
     * 
     * @param string|null $path The asset path (relative like '/uploads/...' or absolute like 'https://...')
     * @return string The full URL
     */
    function asset_url(?string $path): string
    {
        if (empty($path)) {
            return '';
        }
        
        // If it's already an absolute URL, return as-is
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }
        
        // If it starts with /, remove it and use base_url
        // base_url() already handles the leading slash
        $cleanPath = ltrim($path, '/');
        
        return base_url($cleanPath);
    }
}

if (!function_exists('link_url')) {
    /**
     * Returns the full URL for an internal link, handling both relative and absolute URLs.
     * 
     * Use this for CTA buttons, navigation links, etc. stored in the database.
     * If the path is already an absolute URL (starts with http://, https://, mailto:, tel:),
     * it returns it as-is. Otherwise, it prepends 'app/' and uses site_url().
     * 
     * For admin links that already start with 'admin/', they are used as-is.
     * For user-facing relative paths (like 'buy-now'), they become 'app/buy-now'.
     * 
     * @param string|null $path The link path (relative like 'buy-now' or absolute like 'https://...')
     * @return string The full URL
     */
    function link_url(?string $path): string
    {
        if (empty($path)) {
            return '#';
        }
        
        // If it's already an absolute URL or special protocol, return as-is
        if (preg_match('/^(https?:|mailto:|tel:|#)/', $path)) {
            return $path;
        }
        
        // Clean leading slashes
        $cleanPath = ltrim($path, '/');
        
        // If path already starts with 'app/' or 'admin/', use it as-is
        if (preg_match('/^(app|admin)\//', $cleanPath)) {
            return site_url($cleanPath);
        }
        
        // For user-facing relative paths, prepend 'app/'
        return site_url('app/' . $cleanPath);
    }
}

