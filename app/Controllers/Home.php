<?php

namespace App\Controllers;

use App\Models\ProductModel;

/**
 * Class Home
 *
 * Displays the storefront landing page for Playpass customers. This
 * controller retrieves product listings for the "New" and "Featured"
 * sections and passes them to the view. In a production system this
 * would also handle promos, stories and dynamic content, but here
 * simple queries are used for demonstration purposes.
 */
class Home extends BaseController
{
    public function index(): string
    {
        $productModel = new ProductModel();

        // Fetch latest products for "New" section (limit 6)
        $newProducts = $productModel
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->find();

        // Fetch featured products (limit 6)
        $featuredProducts = $productModel
            ->where('is_featured', 1)
            ->limit(6)
            ->find();

        $data = [
            'newProducts'      => $newProducts,
            'featuredProducts' => $featuredProducts,
        ];

        return view('home/index', $data);
    }
}