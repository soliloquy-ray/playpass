<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class NewProductsCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'title' => 'New Arrivals',
            'subtitle' => 'Fresh additions to our store',
            'products' => [
                [
                    'id' => 1,
                    'image' => '/assets/images/placeholder.jpg',
                    'name' => 'New Product',
                    'price' => 24.99,
                    'date' => 'Dec 1, 2025',
                ]
            ],
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\new_products', $data);
    }
}
