<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class FeaturedProductsCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'title' => 'Featured Products',
            'subtitle' => 'Handpicked selections just for you',
            'products' => [
                [
                    'id' => 1,
                    'image' => '/assets/images/placeholder.jpg',
                    'name' => 'Product Name',
                    'price' => 29.99,
                    'rating' => 4.5,
                    'reviews' => 128,
                    'badge' => 'Featured',
                ]
            ],
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\featured_products', $data);
    }
}
