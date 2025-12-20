<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\CustomerSupportModel;

class CustomerSupportCell extends Cell
{
    public function render(array $data = []): string
    {
        $supportModel = new CustomerSupportModel();
        
        // Fetch active support channels from database
        $dbSupports = $supportModel->getChannels();
        
        // Map database supports to view format
        $supports = array_map(function($support) {
            return [
                'icon' => $support['icon'] ?? 'fa-solid fa-circle-question',
                'label' => $support['label'],
                'link' => $support['link'] ?? '#'
            ];
        }, $dbSupports);
        
        // Fallback to default if no supports in database
        if (empty($supports)) {
            $supports = [
                [
                    'icon' => 'fa-solid fa-envelope',
                    'label' => 'Email',
                    'link' => 'mailto:support@playpass.ph'
                ],
                [
                    'icon' => 'fa-solid fa-circle-question',
                    'label' => 'FAQ',
                    'link' => site_url('faq')
                ],
                [
                    'icon' => 'fa-solid fa-comment',
                    'label' => 'Feedback',
                    'link' => site_url('feedback')
                ],
                [
                    'icon' => 'fa-solid fa-phone',
                    'label' => 'Viber',
                    'link' => '#'
                ],
            ];
        }
        
        $defaultData = [
            'title' => 'CUSTOMER SUPPORT',
            'subtitle' => 'Reach Us At',
            'supports' => $supports,
        ];

        $data = array_merge($defaultData, $data);

        return view('cells/customer_support', $data);
    }
}
