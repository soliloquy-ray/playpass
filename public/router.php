<?php

/**
 * Router script for PHP's built-in development server.
 * This handles URL rewriting that would normally be done by Apache's .htaccess
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If requesting a real file (CSS, JS, images, etc.), serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Otherwise, route through index.php (CodeIgniter front controller)
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';
