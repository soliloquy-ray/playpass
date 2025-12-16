<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\BrandModel;
use App\Models\PromosModel;

class ProductsController extends BaseController
{
    public function index()
    {
        $brandModel = new BrandModel();
        $productModel = new ProductModel();
        $promosModel = new PromosModel();
        
        // Fetch new products with brand information - prioritize is_new flag, then fallback to recent products
        // First try to get products with is_new flag set
        $newProducts = $productModel
            ->select('products.*, brands.name as brand_name, brands.logo as brand_logo')
            ->join('brands', 'brands.id = products.brand_id', 'left')
            ->where('products.is_active', 1)
            ->where('products.is_new', 1)
            ->orderBy('products.created_at', 'DESC')
            ->limit(6)
            ->findAll();
        
        // If we don't have 6 products with is_new flag, fill with recent products
        if (count($newProducts) < 6) {
            $remaining = 6 - count($newProducts);
            $existingIds = array_column($newProducts, 'id');
            
            $recentProducts = $productModel
                ->select('products.*, brands.name as brand_name, brands.logo as brand_logo')
                ->join('brands', 'brands.id = products.brand_id', 'left')
                ->where('products.is_active', 1);
            
            if (!empty($existingIds)) {
                $recentProducts->whereNotIn('products.id', $existingIds);
            }
            
            $recentProducts = $recentProducts
                ->orderBy('products.created_at', 'DESC')
                ->limit($remaining)
                ->findAll();
            
            $newProducts = array_merge($newProducts, $recentProducts);
        }
        
        // Fetch featured products with brand information (limited to 6)
        $featuredProducts = $productModel
            ->select('products.*, brands.name as brand_name, brands.logo as brand_logo')
            ->join('brands', 'brands.id = products.brand_id', 'left')
            ->where('products.is_active', 1)
            ->where('products.is_featured', 1)
            ->orderBy('products.sort_order', 'ASC')
            ->orderBy('products.created_at', 'DESC')
            ->limit(6)
            ->findAll();
        
        // Fetch active promos
        $promos = $promosModel->getActivePromos();
        
        // Log for debugging (remove after verification)
        log_message('debug', 'ProductsController: Fetched ' . count($promos) . ' active promos');
        
        return view('products/index', [
            'title' => 'Buy Now - Playpass',
            'brands' => $brandModel->where('is_enabled', 1)->findAll(),
            'newProducts' => $newProducts,
            'featuredProducts' => $featuredProducts,
            'promos' => $promos,
        ]);
    }

    public function fetch()
    {
        try {
            $request = service('request');
            $model   = new ProductModel();

            // Get Filters
            $filters = [
                'brand'    => $request->getGet('brand'),
                'price'    => $request->getGet('price'),
                'duration' => $request->getGet('duration'),
            ];

            $offset = (int) ($request->getGet('offset') ?? 0);
            $limit  = 9; // 3x3 Grid

            log_message('debug', 'ProductsController::fetch - Filters: ' . json_encode($filters) . ', Offset: ' . $offset);

            $products = $model->getFilteredProducts($filters, $limit, $offset);

            log_message('debug', 'ProductsController::fetch - Found ' . count($products) . ' products');

            // Render HTML for cards
            $html = '';
            foreach ($products as $product) {
                // Map brand logo to product logo if product doesn't have specific one
                if(empty($product['logo']) && !empty($product['brand_logo'])) {
                    $product['logo'] = $product['brand_logo'];
                }
                try {
                    $html .= view_cell('App\Cells\ProductCardCell::render', ['product' => $product]);
                } catch (\Exception $e) {
                    log_message('error', 'Error rendering product card: ' . $e->getMessage());
                    log_message('error', 'Product data: ' . json_encode($product));
                }
            }

            return $this->response->setJSON([
                'html'    => $html,
                'count'   => count($products),
                'hasMore' => count($products) >= $limit
            ]);
        } catch (\Exception $e) {
            log_message('error', 'ProductsController::fetch error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Failed to fetch products',
                'message' => $e->getMessage()
            ]);
        }
    }
}