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
                        ['text' => 'About Us', 'url' => '/about'],
                        ['text' => 'Blog', 'url' => '/blog'],
                        ['text' => 'Careers', 'url' => '/careers'],
                        ['text' => 'Press', 'url' => '/press'],
                    ]
                ],
                [
                    'title' => 'Products',
                    'links' => [
                        ['text' => 'Gaming Bundles', 'url' => '/products?category=gaming'],
                        ['text' => 'Subscriptions', 'url' => '/products?category=subscriptions'],
                        ['text' => 'Gift Cards', 'url' => '/products?category=gifts'],
                        ['text' => 'Bundles', 'url' => '/products?category=bundles'],
                    ]
                ],
                [
                    'title' => 'Support',
                    'links' => [
                        ['text' => 'Help Center', 'url' => '/help'],
                        ['text' => 'Contact', 'url' => '/contact'],
                        ['text' => 'FAQ', 'url' => '/faq'],
                        ['text' => 'Status', 'url' => '/status'],
                    ]
                ],
                [
                    'title' => 'Legal',
                    'links' => [
                        ['text' => 'Terms of Service', 'url' => '/terms'],
                        ['text' => 'Privacy Policy', 'url' => '/privacy'],
                        ['text' => 'Cookie Policy', 'url' => '/cookies'],
                        ['text' => 'Refund Policy', 'url' => '/refunds'],
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
