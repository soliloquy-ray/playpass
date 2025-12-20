<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\HowItWorksModel;

class HowItWorksCell extends Cell
{
    public function render(array $data = []): string
    {
        $howItWorksModel = new HowItWorksModel();
        
        // Fetch active steps from database
        $dbSteps = $howItWorksModel->getActiveSteps();
        
        // Map database steps to view format
        $steps = array_map(function($step, $index) {
            return [
                'number' => $index + 1,
                'title' => $step['title'],
                'description' => $step['description'],
                'icon' => $step['icon'] ?? '😀'
            ];
        }, $dbSteps, array_keys($dbSteps));
        
        // Fallback to default if no steps in database
        if (empty($steps)) {
            $steps = [
                [
                    'number' => 1,
                    'title' => 'Browse',
                    'description' => 'Explore our collection of gaming bundles and subscriptions',
                    'icon' => '😀'
                ],
                [
                    'number' => 2,
                    'title' => 'Select',
                    'description' => 'Choose the products and quantities you want',
                    'icon' => '😀'
                ],
                [
                    'number' => 3,
                    'title' => 'Pay',
                    'description' => 'Complete your purchase securely',
                    'icon' => '😀'
                ],
                [
                    'number' => 4,
                    'title' => 'Receive',
                    'description' => 'Get your digital products instantly',
                    'icon' => '😀'
                ],
            ];
        }
        
        $defaultData = [
            'title' => 'How It Works',
            'subtitle' => 'Get your digital products in 4 simple steps',
            'steps' => $steps,
        ];

        $data = array_merge($defaultData, $data);

        return view('cells/how_it_works', $data);
    }
}
