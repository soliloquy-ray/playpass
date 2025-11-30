<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LoyaltyTierSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code'                => 'bronze',
                'name'                => 'Bronze',
                'description'         => 'Entry level membership tier',
                'level'               => 1,
                'min_purchases'       => 0,
                'min_amount_spent'    => 0.00,
                'min_referrals'       => 0,
                'points_multiplier'   => 1.00,
                'discount_percentage' => 0.00,
                'color_code'          => '#CD7F32',
                'is_active'           => 1,
            ],
            [
                'code'                => 'silver',
                'name'                => 'Silver',
                'description'         => 'Mid-tier membership with enhanced benefits',
                'level'               => 2,
                'min_purchases'       => 10,
                'min_amount_spent'    => 5000.00,
                'min_referrals'       => 2,
                'points_multiplier'   => 1.25,
                'discount_percentage' => 2.00,
                'color_code'          => '#C0C0C0',
                'is_active'           => 1,
            ],
            [
                'code'                => 'gold',
                'name'                => 'Gold',
                'description'         => 'Premium membership tier with maximum benefits',
                'level'               => 3,
                'min_purchases'       => 25,
                'min_amount_spent'    => 15000.00,
                'min_referrals'       => 5,
                'points_multiplier'   => 1.50,
                'discount_percentage' => 5.00,
                'color_code'          => '#FFD700',
                'is_active'           => 1,
            ],
        ];

        $this->db->table('loyalty_tiers')->insertBatch($data);
    }
}