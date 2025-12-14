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

        // 1. Create the Brand (Viu)
        $brandData = [
            'name'       => 'Viu',
            'logo'       => '/assets/brands/viu_logo.png', // Ensure you have this image
            'is_enabled' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert Brand and get the ID
        $this->db->table('brands')->insert($brandData);
        $viuBrandId = $this->db->insertID();

        // 2. Create the Products linked to Viu
        $products = [
            [
                'brand_id'          => $viuBrandId,
                'sku'               => 'VIU-3DAY',
                'name'              => '3 Days Viu Premium',
                'description'       => 'Access premium Asian content for 3 days.',
                'price'             => 29.00,
                
                // Visual Styling for the Card
                'bg_color'          => '#FFC107', // The Viu Yellow
                'thumbnail_url'     => '/assets/brands/viu_thumb.png',
                'badge_label'       => 'HOT',
                
                // Logic
                'is_featured'       => 1,
                'is_active'         => 1,
                'sort_order'        => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'brand_id'          => $viuBrandId,
                'sku'               => 'VIU-7DAY',
                'name'              => '7 Days Viu Premium',
                'description'       => 'One week of unlimited downloading and streaming.',
                'price'             => 50.00,
                
                'bg_color'          => '#FFC107',
                'thumbnail_url'     => '/assets/brands/viu_thumb.png',
                'badge_label'       => null,
                
                'is_featured'       => 1,
                'is_active'         => 1,
                'sort_order'        => 2,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'brand_id'          => $viuBrandId,
                'sku'               => 'VIU-30DAY',
                'name'              => '30 Days Viu Premium',
                'description'       => 'Best value! Full month of premium access.',
                'price'             => 169.00,
                
                'bg_color'          => '#FFC107',
                'thumbnail_url'     => '/assets/brands/viu_thumb.png',
                'badge_label'       => 'BEST VALUE',
                
                'is_featured'       => 1,
                'is_active'         => 1,
                'sort_order'        => 3,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert all products
        $this->db->table('products')->insertBatch($products);

    }
}