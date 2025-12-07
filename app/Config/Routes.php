<?php

use CodeIgniter\Router\RouteCollection;

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 * This file defines all of the application routes for the customer-facing
 * portion of the Playpass ecommerce site. It overrides the default
 * CodeIgniter routes and adds endpoints for registration, login, home
 * content, and the customer account page.
 *
 * The controllers referenced here live in `app/Controllers` and follow
 * CodeIgniter 4 conventions. Additional routes can be added as needed.
 */

/** @var RouteCollection $routes */
$routes->post('checkout/process', 'CheckoutController::process');
$routes->get('/', 'Home::index');

$routes->group('admin', ['filter' => 'AdminGuard'], function($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->resource('products', ['controller' => 'Admin\ProductController']);
    $routes->resource('vouchers', ['controller' => 'Admin\VoucherController']);
    $routes->resource('content',  ['controller' => 'Admin\ContentController']);
    $routes->get('reports', 'Admin\ReportsController::index');
});

// Authentication routes
$routes->get('register', 'Auth::showRegister');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::showLogin');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Customer account dashboard
$routes->get('account', 'User::index');