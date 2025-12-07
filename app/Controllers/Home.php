<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ArticleModel;

/**
 * Class Home
 *
 * Displays the storefront landing page.
 * Refactored to fetch Products and Articles for the component-based view.
 */
class Home extends BaseController
{
    public function index(): string
    {
        $productModel = new ProductModel();
        $articleModel = new ArticleModel();

        // Fetch products
        $newProducts = $productModel
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->find();

        $featuredProducts = $productModel
            ->where('is_featured', 1) // Ensure your DB has this column or remove this clause
            ->limit(6)
            ->find();

        // Fetch articles
        $latestArticles = $articleModel->getPublished(3);

        $data = [
            'newProducts'      => $newProducts,
            'featuredProducts' => $featuredProducts,
            'latestArticles'   => $latestArticles,
            'title'            => 'Playpass | Home'
        ];

        return view('home/index', $data);
    }
}