<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel; // You created this earlier
use App\Models\ProductModel; // You created this earlier

class ProductController extends BaseController
{
    public function view($brandId)
    {
        $brandModel   = new BrandModel();
        $productModel = new ProductModel();

        // 1. Fetch the Brand (e.g., Viu)
        $brand = $brandModel->find($brandId);

        if (!$brand) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Brand not found");
        }

        // 2. Fetch the 'Denominations' (Products) for this Brand
        // We order by price so they appear nicely (Low to High)
        $products = $productModel->where('brand_id', $brandId)
                                 ->where('is_active', 1)
                                 ->orderBy('price', 'ASC')
                                 ->findAll();

        // 3. Define Payment Channels (Hardcoded for now, or fetch from DB if you have a model)
        $paymentChannels = [
            ['code' => 'gcash', 'name' => 'GCash', 'logo' => '/assets/payment/gcash.png'],
            ['code' => 'maya', 'name' => 'Maya', 'logo' => '/assets/payment/maya.png'],
            ['code' => 'dragon', 'name' => 'DragonPay', 'logo' => '/assets/payment/dragonpay.png'],
            ['code' => '7eleven', 'name' => '7 Eleven', 'logo' => '/assets/payment/7eleven.png'],
            ['code' => 'cat', 'name' => 'Coming Soon', 'logo' => '/assets/payment/cat.png'],
        ];

        // 4. Prepare Data for the View
        $data = [
            'title' => $brand['name'] . ' - Playpass',
            'brand' => $brand,
            'products' => $products,
            'paymentChannels' => $paymentChannels,
        ];

        return view('product', $data);
    }
}