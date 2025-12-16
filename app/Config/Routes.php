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

// ============================================
// ROOT ROUTE - Redirect to /app
// ============================================
$routes->get('/', function() {
    return redirect()->to('/app');
});

// ============================================
// PUBLIC APP ROUTES (User-facing)
// ============================================
$routes->group('app', function($routes) {
    // Home
    $routes->get('/', 'Home::index');
    $routes->get('/home', 'Home::index');
    
    // Checkout
    $routes->post('checkout/process', 'CheckoutController::process');
    
    // Product Routes
    $routes->get('buy/(:num)', 'ProductController::view/$1');
    $routes->get('buy-now', 'ProductsController::index');
    $routes->get('products/fetch', 'ProductsController::fetch');
    
    // Story Routes
    $routes->get('stories', 'StoriesController::index');
    $routes->get('stories/fetch', 'StoriesController::fetch');
    $routes->get('stories/(:segment)', 'StoriesController::show/$1'); // Individual story by slug
    
    // Authentication routes
    $routes->get('register', 'Auth::showRegister');
    $routes->post('register', 'Auth::register');
    $routes->get('login', 'Auth::showLogin');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('forgot-password', 'Auth::showForgotPassword');
    $routes->post('forgot-password', 'Auth::forgotPassword');
    $routes->get('verify-email', 'Auth::showVerifyEmail');
    $routes->post('verify-email', 'Auth::verifyEmail');
    $routes->post('resend-verification', 'Auth::resendVerification');
    $routes->get('resend-verification', 'Auth::resendVerification');
    
    // OAuth Social Login routes
    $routes->get('auth/google', 'Auth::googleRedirect');
    $routes->get('auth/google/callback', 'Auth::googleCallback');
    $routes->get('auth/facebook', 'Auth::facebookRedirect');
    $routes->get('auth/facebook/callback', 'Auth::facebookCallback');
    
    // Customer account dashboard
    $routes->get('account', 'User::index');
    $routes->get('account/edit', 'User::editProfile');
    $routes->post('account/update', 'User::updateProfile');
    $routes->get('account/voucher/use/(:num)', 'User::useVoucher/$1');
});

// ============================================
// ADMIN AUTH ROUTES (Public, no guard)
// ============================================
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('login', 'AuthController::showLogin');
    $routes->post('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout');
});

// ============================================
// ADMIN CMS ROUTES (Protected by AdminGuard)
// ============================================
$routes->group('admin', ['filter' => 'AdminGuard', 'namespace' => 'App\Controllers\Admin'], function($routes) {
    
    // Dashboard
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('/', 'DashboardController::index'); // Redirect /admin to /admin/dashboard
    
    // Top Banner
    $routes->get('banner', 'BannerController::index');
    $routes->post('banner/save', 'BannerController::save');
    
    // Carousel Slides
    $routes->get('carousel', 'CarouselController::index');
    $routes->get('carousel/new', 'CarouselController::new');
    $routes->post('carousel/create', 'CarouselController::create');
    $routes->get('carousel/edit/(:num)', 'CarouselController::edit/$1');
    $routes->post('carousel/update/(:num)', 'CarouselController::update/$1');
    $routes->post('carousel/delete/(:num)', 'CarouselController::delete/$1');
    $routes->post('carousel/update-order', 'CarouselController::updateOrder');
    
    // Products
    $routes->get('products', 'ProductController::index');
    $routes->get('products/new', 'ProductController::new');
    $routes->post('products/create', 'ProductController::create');
    $routes->get('products/edit/(:num)', 'ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'ProductController::update/$1');
    $routes->post('products/delete/(:num)', 'ProductController::delete/$1');
    
    // Brands
    $routes->get('brands', 'BrandController::index');
    $routes->get('brands/new', 'BrandController::new');
    $routes->post('brands/create', 'BrandController::create');
    $routes->get('brands/edit/(:num)', 'BrandController::edit/$1');
    $routes->post('brands/update/(:num)', 'BrandController::update/$1');
    $routes->post('brands/delete/(:num)', 'BrandController::delete/$1');
    
    // Stories
    $routes->get('stories', 'StoryController::index');
    $routes->get('stories/new', 'StoryController::new');
    $routes->post('stories/create', 'StoryController::create');
    $routes->get('stories/edit/(:num)', 'StoryController::edit/$1');
    $routes->post('stories/update/(:num)', 'StoryController::update/$1');
    $routes->post('stories/delete/(:num)', 'StoryController::delete/$1');
    
    // Promos
    $routes->get('promos', 'PromoController::index');
    $routes->get('promos/new', 'PromoController::new');
    $routes->post('promos/create', 'PromoController::create');
    $routes->get('promos/edit/(:num)', 'PromoController::edit/$1');
    $routes->post('promos/update/(:num)', 'PromoController::update/$1');
    $routes->post('promos/delete/(:num)', 'PromoController::delete/$1');
    
    // How It Works
    $routes->get('how-it-works', 'HowItWorksController::index');
    $routes->get('how-it-works/new', 'HowItWorksController::new');
    $routes->post('how-it-works/create', 'HowItWorksController::create');
    $routes->get('how-it-works/edit/(:num)', 'HowItWorksController::edit/$1');
    $routes->post('how-it-works/update/(:num)', 'HowItWorksController::update/$1');
    $routes->post('how-it-works/delete/(:num)', 'HowItWorksController::delete/$1');
    $routes->post('how-it-works/update-order', 'HowItWorksController::updateOrder');
    
    // Customer Support
    $routes->get('customer-support', 'CustomerSupportController::index');
    $routes->get('customer-support/new', 'CustomerSupportController::new');
    $routes->post('customer-support/create', 'CustomerSupportController::create');
    $routes->get('customer-support/edit/(:num)', 'CustomerSupportController::edit/$1');
    $routes->post('customer-support/update/(:num)', 'CustomerSupportController::update/$1');
    $routes->post('customer-support/delete/(:num)', 'CustomerSupportController::delete/$1');
    
    // Vouchers
    $routes->get('vouchers', 'VoucherController::index');
    $routes->get('vouchers/new', 'VoucherController::new');
    $routes->post('vouchers/create', 'VoucherController::create');
    $routes->get('vouchers/edit/(:num)', 'VoucherController::edit/$1');
    $routes->post('vouchers/update/(:num)', 'VoucherController::update/$1');
    $routes->post('vouchers/delete/(:num)', 'VoucherController::delete/$1');
    $routes->get('vouchers/codes/(:num)', 'VoucherController::codes/$1');
    $routes->post('vouchers/generate-codes/(:num)', 'VoucherController::generateCodes/$1');
    
    // Orders (placeholder)
    $routes->get('orders', 'DashboardController::index'); // TODO: Create OrderController
    
    // Users (placeholder)
    $routes->get('users', 'DashboardController::index'); // TODO: Create UserController
    
    // Reports (placeholder)
    $routes->get('reports', 'DashboardController::index'); // TODO: Create ReportsController
});
