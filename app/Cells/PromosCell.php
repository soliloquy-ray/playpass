<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class PromosCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'bannerTitle' => 'Special Offer',
            'bannerDescription' => 'Limited time deals on your favorite products',
            'bannerDiscount' => '50% OFF',
            'bannerExpiry' => 'Ends Dec 15',
            'bannerCTA' => 'Shop Now',
            'bannerImage' => '/assets/images/placeholder.jpg',
            'promos' => [
                [
                    'title' => 'Promo 1',
                    'discount' => '30% OFF',
                    'code' => 'PROMO30',
                    'image' => '/assets/images/placeholder.jpg',
                    'minSpend' => 50,
                ]
            ],
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\promos', $data);
    }
}
