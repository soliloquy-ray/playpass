<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class CustomerSupportCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'title' => 'Need Help?',
            'subtitle' => 'We\'re here to support you',
            'supports' => [
                [
                    'icon' => 'chat',
                    'title' => 'Live Chat',
                    'description' => 'Chat with our support team',
                    'cta' => 'Start Chat',
                ],
                [
                    'icon' => 'email',
                    'title' => 'Email Support',
                    'description' => 'Send us your questions',
                    'cta' => 'Send Email',
                ],
                [
                    'icon' => 'phone',
                    'title' => 'Phone Support',
                    'description' => 'Call us directly',
                    'cta' => 'Call Now',
                ],
                [
                    'icon' => 'faq',
                    'title' => 'FAQ',
                    'description' => 'Find answers quickly',
                    'cta' => 'View FAQ',
                ],
            ],
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\customer_support', $data);
    }
}
