<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StoryModel;
use App\Models\CarouselSlideModel;
use App\Models\PromosModel;

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
        $storyModel = new StoryModel();
        $carouselModel = new CarouselSlideModel();
        $promosModel = new PromosModel();

        // Fetch carousel slides
        $carouselSlides = $carouselModel->getActiveSlides();

        // Fetch products
        $newProducts = $productModel
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->find();

        $featuredProducts = $productModel
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->find();

        // Fetch articles
        $latestStories = $storyModel->getPublished(3);

        // Fetch active promos
        $promos = $promosModel->getActivePromos();

        $data = [
            'carouselSlides'   => $carouselSlides,
            'newProducts'      => $newProducts,
            'featuredProducts' => $featuredProducts,
            'latestStories'   => $latestStories,
            'promos'           => $promos,
            'title'            => 'Playpass | Home'
        ];

        return view('home/index', $data);
    }
}