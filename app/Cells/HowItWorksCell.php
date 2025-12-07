<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class HowItWorksCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'title' => 'How It Works',
            'subtitle' => 'Get your digital products in 4 simple steps',
            'steps' => [
                [
                    'number' => 1,
                    'title' => 'Browse',
                    'description' => 'Explore our collection of gaming bundles and subscriptions',
                ],
                [
                    'number' => 2,
                    'title' => 'Select',
                    'description' => 'Choose the products and quantities you want',
                ],
                [
                    'number' => 3,
                    'title' => 'Pay',
                    'description' => 'Complete your purchase securely',
                ],
                [
                    'number' => 4,
                    'title' => 'Receive',
                    'description' => 'Get your digital products instantly',
                ],
            ],
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\how_it_works', $data);
    }
}
