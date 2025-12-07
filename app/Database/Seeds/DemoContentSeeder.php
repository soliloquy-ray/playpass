<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run()
    {
        // 1. Create a Default Admin User (for Article Authorship)
        $userId = $this->db->table('users')->insert([
            'email'          => 'admin@playpass.ph',
            'password_hash'  => password_hash('admin123', PASSWORD_DEFAULT),
            'first_name'     => 'Super',
            'last_name'      => 'Admin',
            'role'           => 'admin',
            'current_points_balance' => 1000,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        // Get the ID of the user we just created (usually 1)
        $authorId = $this->db->insertID();

        // 2. Seed Products
        $products = [
            [
                'sku'               => 'TTM-001',
                'name'              => 'To the Moon',
                'description'       => 'A story-driven experience about two doctors traversing through a dying man\'s memories to artificially fulfill his last wish.',
                'price'             => 499.00,
                'thumbnail_url'     => 'https://images.unsplash.com/photo-1534423861386-85a16f5d13fd?auto=format&fit=crop&w=400&q=80', // Placeholder Space Image
                'points_to_earn'    => 50,
                'is_featured'       => 1,
                'is_bundle'         => 0,
                'maya_product_code' => 'MAYA-TTM-001',
                'is_active'         => 1,
            ],
            [
                'sku'               => 'VP-1000',
                'name'              => '1000 Valorant Points',
                'description'       => 'Unlock skins and battle passes in Valorant.',
                'price'             => 300.00,
                'thumbnail_url'     => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=400&q=80', // Placeholder Gaming Image
                'points_to_earn'    => 30,
                'is_featured'       => 0,
                'is_bundle'         => 0,
                'maya_product_code' => 'MAYA-VP-1000',
                'is_active'         => 1,
            ],
            [
                'sku'               => 'MLBB-500',
                'name'              => '500 Mobile Legends Diamonds',
                'description'       => 'Purchase heroes and skins in Mobile Legends: Bang Bang.',
                'price'             => 450.00,
                'thumbnail_url'     => 'https://images.unsplash.com/photo-1593305841991-05c2e407f0b5?auto=format&fit=crop&w=400&q=80', // Placeholder MOBA Image
                'points_to_earn'    => 45,
                'is_featured'       => 1, // Featured to test the UI
                'is_bundle'         => 0,
                'maya_product_code' => 'MAYA-MLBB-500',
                'is_active'         => 1,
            ],
            [
                'sku'               => 'BUNDLE-START',
                'name'              => 'FPS Starter Bundle',
                'description'       => 'Get 1000 VP and a special skin voucher!',
                'price'             => 850.00,
                'thumbnail_url'     => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=400&q=80', // Placeholder Bundle Image
                'points_to_earn'    => 100, // High points for bundle
                'is_featured'       => 0,
                'is_bundle'         => 1, // Testing the BUNDLE badge
                'maya_product_code' => 'MAYA-BND-001',
                'is_active'         => 1,
            ],
            [
                'sku'               => 'STM-500',
                'name'              => 'Steam Wallet PHP 500',
                'description'       => 'Add funds to your Steam Wallet.',
                'price'             => 500.00,
                'thumbnail_url'     => 'https://images.unsplash.com/photo-1612287232817-60286063673d?auto=format&fit=crop&w=400&q=80', // Placeholder Controller
                'points_to_earn'    => 50,
                'is_featured'       => 0,
                'is_bundle'         => 0,
                'maya_product_code' => 'MAYA-STM-500',
                'is_active'         => 1,
            ]
        ];

        // Insert Products
        foreach ($products as $product) {
            $product['created_at'] = date('Y-m-d H:i:s');
            $product['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('products')->insert($product);
        }

        // 3. Seed Articles
        $articles = [
            [
                'title'     => 'How to Redeem Your Points',
                'slug'      => 'how-to-redeem-points',
                'content'   => '<p>Earning points on Playpass is easy! Simply purchase any product with a "Points" badge, and they will be automatically credited to your account. You can use these points to get discounts on future purchases or redeem exclusive vouchers.</p>',
                'status'    => 'published',
                'author_id' => $authorId,
            ],
            [
                'title'     => 'The Summer Sale is Here!',
                'slug'      => 'summer-sale-2025',
                'content'   => '<p>Get ready for the biggest sale of the year. Up to 50% off on selected bundles and double points on all FPS game credits. Don\'t miss out!</p>',
                'status'    => 'published',
                'author_id' => $authorId,
            ],
            [
                'title'     => 'New Game Release: To the Moon',
                'slug'      => 'new-release-to-the-moon',
                'content'   => '<p>We are excited to announce that "To the Moon" is now available on Playpass. Experience the emotional journey that has captured the hearts of millions.</p>',
                'status'    => 'draft', // This should NOT appear in the UI
                'author_id' => $authorId,
            ],
        ];

        // Insert Articles
        foreach ($articles as $article) {
            $article['created_at'] = date('Y-m-d H:i:s');
            $article['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('cms_articles')->insert($article);
        }
    }
}