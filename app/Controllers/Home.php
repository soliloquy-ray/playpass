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

        // Fetch products with brand information
        $newProducts = $productModel
            ->select('products.*, brands.name as brand_name, brands.logo as brand_logo')
            ->join('brands', 'brands.id = products.brand_id', 'left')
            ->where('products.is_active', 1)
            ->orderBy('products.created_at', 'DESC')
            ->limit(6)
            ->findAll();

        $featuredProducts = $productModel
            ->select('products.*, brands.name as brand_name, brands.logo as brand_logo')
            ->join('brands', 'brands.id = products.brand_id', 'left')
            ->where('products.is_active', 1)
            ->where('products.is_featured', 1)
            ->orderBy('products.sort_order', 'ASC')
            ->orderBy('products.created_at', 'DESC')
            ->limit(6)
            ->findAll();

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