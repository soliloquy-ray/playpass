<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class FooterCell extends Cell
{
    public function render(array $data = []): string
    {
        $defaultData = [
            'logo' => 'PlayPass',
            'tagline' => 'Your ultimate gaming & digital products marketplace',
            'columns' => [
                [
                    'title' => 'Company',
                    'links' => [
                        ['text' => 'About Us', 'url' => site_url('about')],
                        ['text' => 'Blog', 'url' => site_url('blog')],
                        ['text' => 'Careers', 'url' => site_url('careers')],
                        ['text' => 'Press', 'url' => site_url('press')],
                    ]
                ],
                [
                    'title' => 'Products',
                    'links' => [
                        ['text' => 'Gaming Bundles', 'url' => site_url('app/buy-now?category=gaming')],
                        ['text' => 'Subscriptions', 'url' => site_url('app/buy-now?category=subscriptions')],
                        ['text' => 'Gift Cards', 'url' => site_url('app/buy-now?category=gifts')],
                        ['text' => 'Bundles', 'url' => site_url('app/buy-now?category=bundles')],
                    ]
                ],
                [
                    'title' => 'Support',
                    'links' => [
                        ['text' => 'Help Center', 'url' => site_url('help')],
                        ['text' => 'Contact', 'url' => site_url('contact')],
                        ['text' => 'FAQ', 'url' => site_url('faq')],
                        ['text' => 'Status', 'url' => site_url('status')],
                    ]
                ],
                [
                    'title' => 'Legal',
                    'links' => [
                        ['text' => 'Terms of Service', 'url' => site_url('terms')],
                        ['text' => 'Privacy Policy', 'url' => site_url('privacy')],
                        ['text' => 'Cookie Policy', 'url' => site_url('cookies')],
                        ['text' => 'Refund Policy', 'url' => site_url('refunds')],
                    ]
                ],
            ],
            'social' => [
                ['icon' => 'facebook', 'url' => 'https://facebook.com/playpass'],
                ['icon' => 'twitter', 'url' => 'https://twitter.com/playpass'],
                ['icon' => 'instagram', 'url' => 'https://instagram.com/playpass'],
                ['icon' => 'discord', 'url' => 'https://discord.gg/playpass'],
            ],
            'copyright' => '© 2025 PlayPass. All rights reserved.',
        ];

        $data = array_merge($defaultData, $data);

        return view('App\Cells\footer', $data);
    }
}
