<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BrandProductSeeder extends Seeder
{
    /**
     * Seeds brands and products based on the provided brand designs
     * 
     * Usage:
     * php spark db:seed BrandProductSeeder
     */
    public function run()
    {
        // Define brands with their properties
        $brands = [
            [
                'name' => 'Viu',
                'logo' => '/assets/brands/viu.png',
                'bg_color' => '#ffc107', // Yellow
            ],
            [
                'name' => 'Viva Max',
                'logo' => '/assets/brands/viva-max.png',
                'bg_color' => '#1e3a8a', // Dark blue
            ],
            [
                'name' => 'Cignal',
                'logo' => '/assets/brands/cignal.png',
                'bg_color' => '#dc2626', // Bright red
            ],
            [
                'name' => 'Blast TV',
                'logo' => '/assets/brands/blast-tv.png',
                'bg_color' => '#6b21a8', // Dark purple
            ],
            [
                'name' => 'Cignal Play',
                'logo' => '/assets/brands/cignal-play.png',
                'bg_color' => '#000000', // Black
            ],
            [
                'name' => 'Viva One',
                'logo' => '/assets/brands/viva-one.png',
                'bg_color' => '#2d5016', // Teal/Blue-green
            ],
            [
                'name' => 'Pilipinas Live',
                'logo' => '/assets/brands/pilipinas-live.png',
                'bg_color' => '#000000', // Black
            ],
            [
                'name' => 'Satlite',
                'logo' => '/assets/brands/satlite.png',
                'bg_color' => '#ffffff', // White
            ],
            [
                'name' => 'Disney+',
                'logo' => '/assets/brands/disney-plus.png',
                'bg_color' => '#113ccf', // Deep blue
            ],
            [
                'name' => 'HBO Max',
                'logo' => '/assets/brands/hbo-max.png',
                'bg_color' => '#1e40af', // Gradient blue
            ],
        ];

        $brandIds = [];
        
        // Create or update brands
        foreach ($brands as $brandData) {
            $existingBrand = $this->db->table('brands')
                ->where('name', $brandData['name'])
                ->get()
                ->getRowArray();
            
            if ($existingBrand) {
                // Update existing brand
                $this->db->table('brands')
                    ->where('id', $existingBrand['id'])
                    ->update([
                        'logo' => $brandData['logo'],
                        'is_enabled' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                $brandIds[$brandData['name']] = $existingBrand['id'];
                echo "✓ Updated brand: {$brandData['name']}\n";
            } else {
                // Create new brand
                $this->db->table('brands')->insert([
                    'name' => $brandData['name'],
                    'logo' => $brandData['logo'],
                    'is_enabled' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $brandIds[$brandData['name']] = $this->db->insertID();
                echo "✓ Created brand: {$brandData['name']}\n";
            }
        }

        // Define products for each brand (3 products per brand with different durations)
        $productsByBrand = [
            'Viu' => [
                ['duration' => '3 Days', 'price' => 29.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 50.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 169.00, 'sku_suffix' => '30DAY'],
            ],
            'Viva Max' => [
                ['duration' => '3 Days', 'price' => 35.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 65.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 199.00, 'sku_suffix' => '30DAY'],
            ],
            'Cignal' => [
                ['duration' => '3 Days', 'price' => 40.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 75.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 249.00, 'sku_suffix' => '30DAY'],
            ],
            'Blast TV' => [
                ['duration' => '3 Days', 'price' => 25.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 45.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 149.00, 'sku_suffix' => '30DAY'],
            ],
            'Cignal Play' => [
                ['duration' => '3 Days', 'price' => 30.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 55.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 179.00, 'sku_suffix' => '30DAY'],
            ],
            'Viva One' => [
                ['duration' => '3 Days', 'price' => 28.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 49.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 159.00, 'sku_suffix' => '30DAY'],
            ],
            'Pilipinas Live' => [
                ['duration' => '3 Days', 'price' => 32.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 59.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 189.00, 'sku_suffix' => '30DAY'],
            ],
            'Satlite' => [
                ['duration' => '3 Days', 'price' => 27.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 48.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 154.00, 'sku_suffix' => '30DAY'],
            ],
            'Disney+' => [
                ['duration' => '3 Days', 'price' => 45.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 85.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 299.00, 'sku_suffix' => '30DAY'],
            ],
            'HBO Max' => [
                ['duration' => '3 Days', 'price' => 42.00, 'sku_suffix' => '3DAY'],
                ['duration' => '7 Days', 'price' => 79.00, 'sku_suffix' => '7DAY'],
                ['duration' => '30 Days', 'price' => 279.00, 'sku_suffix' => '30DAY'],
            ],
        ];

        // Create products for each brand
        $allProducts = [];
        $productCounter = 0;
        
        foreach ($brands as $brandData) {
            $brandName = $brandData['name'];
            $brandId = $brandIds[$brandName];
            $brandProducts = $productsByBrand[$brandName] ?? [];
            
            foreach ($brandProducts as $index => $productData) {
                $sku = strtoupper(str_replace(' ', '', str_replace('+', 'PLUS', $brandName))) . '-' . $productData['sku_suffix'];
                $productName = $productData['duration'] . ' ' . $brandName . ' Premium';
                
                // Check if product exists
                $existingProduct = $this->db->table('products')
                    ->where('sku', $sku)
                    ->get()
                    ->getRowArray();
                
                $productRecord = [
                    'brand_id' => $brandId,
                    'sku' => $sku,
                    'name' => $productName,
                    'description' => "Access {$brandName} premium content for {$productData['duration']}.",
                    'price' => $productData['price'],
                    'bg_color' => $brandData['bg_color'],
                    'thumbnail_url' => '/assets/products/' . strtolower(str_replace(' ', '-', $brandName)) . '-thumb.jpg',
                    'badge_label' => $index === 2 ? 'BEST VALUE' : ($index === 0 ? 'NEW' : null),
                    'points_to_earn' => (int)($productData['price'] * 0.1), // 10% of price as points
                    'is_bundle' => 0,
                    'is_featured' => $index === 0 ? 1 : 0, // First product is featured
                    'is_new' => $index === 0 ? 1 : 0, // First product is new
                    'sort_order' => $index + 1,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s', strtotime("-{$productCounter} days")),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                
                if ($existingProduct) {
                    // Update existing product
                    $this->db->table('products')
                        ->where('id', $existingProduct['id'])
                        ->update($productRecord);
                    echo "  ✓ Updated product: {$productName}\n";
                } else {
                    // Create new product
                    $this->db->table('products')->insert($productRecord);
                    echo "  ✓ Created product: {$productName}\n";
                }
                
                $productCounter++;
            }
        }
        
        echo "\n✓ Seeding completed! Created/Updated " . count($brands) . " brands with products.\n";
    }
}
