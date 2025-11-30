<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // General Settings
            [
                'group'       => 'general',
                'key'         => 'site_name',
                'value'       => 'Playpass',
                'type'        => 'string',
                'label'       => 'Site Name',
                'description' => 'The name of the website',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'general',
                'key'         => 'site_tagline',
                'value'       => 'Your Digital Marketplace',
                'type'        => 'string',
                'label'       => 'Site Tagline',
                'description' => 'A short description or tagline',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'general',
                'key'         => 'timezone',
                'value'       => 'Asia/Manila',
                'type'        => 'string',
                'label'       => 'Timezone',
                'description' => 'Default timezone for the application',
                'is_public'   => 0,
                'is_editable' => 1,
            ],
            [
                'group'       => 'general',
                'key'         => 'currency',
                'value'       => 'PHP',
                'type'        => 'string',
                'label'       => 'Currency',
                'description' => 'Default currency code',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'general',
                'key'         => 'currency_symbol',
                'value'       => '₱',
                'type'        => 'string',
                'label'       => 'Currency Symbol',
                'description' => 'Currency symbol to display',
                'is_public'   => 1,
                'is_editable' => 1,
            ],

            // Points Settings
            [
                'group'       => 'points',
                'key'         => 'points_to_peso_ratio',
                'value'       => '100',
                'type'        => 'integer',
                'label'       => 'Points to Peso Ratio',
                'description' => 'Number of points equal to 1 peso (default: 100 points = 1 peso)',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'points',
                'key'         => 'points_expiry_days',
                'value'       => '365',
                'type'        => 'integer',
                'label'       => 'Points Expiry Days',
                'description' => 'Number of days before points expire (default: 365 days = 1 year)',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'points',
                'key'         => 'min_points_redemption',
                'value'       => '100',
                'type'        => 'integer',
                'label'       => 'Minimum Points Redemption',
                'description' => 'Minimum points required for redemption',
                'is_public'   => 1,
                'is_editable' => 1,
            ],

            // Birthday Settings
            [
                'group'       => 'birthday',
                'key'         => 'birthday_bonus_points',
                'value'       => '1000',
                'type'        => 'integer',
                'label'       => 'Birthday Bonus Points',
                'description' => 'Points awarded on user birthday',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'birthday',
                'key'         => 'birthday_points_expiry_type',
                'value'       => 'end_of_month',
                'type'        => 'string',
                'label'       => 'Birthday Points Expiry',
                'description' => 'When birthday points expire (end_of_month or days)',
                'is_public'   => 0,
                'is_editable' => 1,
            ],

            // Referral Settings
            [
                'group'       => 'referral',
                'key'         => 'referrer_points',
                'value'       => '500',
                'type'        => 'integer',
                'label'       => 'Referrer Points',
                'description' => 'Points awarded to referrer on successful referral',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'referral',
                'key'         => 'referee_points',
                'value'       => '500',
                'type'        => 'integer',
                'label'       => 'Referee Points',
                'description' => 'Points awarded to referee on successful referral',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'referral',
                'key'         => 'referral_min_purchase',
                'value'       => '0',
                'type'        => 'float',
                'label'       => 'Referral Minimum Purchase',
                'description' => 'Minimum purchase amount to complete referral',
                'is_public'   => 1,
                'is_editable' => 1,
            ],

            // Cart Settings
            [
                'group'       => 'cart',
                'key'         => 'cart_expiry_hours',
                'value'       => '24',
                'type'        => 'integer',
                'label'       => 'Cart Expiry Hours',
                'description' => 'Hours before an inactive cart expires',
                'is_public'   => 0,
                'is_editable' => 1,
            ],
            [
                'group'       => 'cart',
                'key'         => 'inventory_reserve_minutes',
                'value'       => '15',
                'type'        => 'integer',
                'label'       => 'Inventory Reserve Minutes',
                'description' => 'Minutes to reserve inventory during checkout',
                'is_public'   => 0,
                'is_editable' => 1,
            ],

            // Kyuubi API Settings
            [
                'group'       => 'kyuubi',
                'key'         => 'api_base_url',
                'value'       => 'https://kyuubi-external-api-staging.voyagerapis.com',
                'type'        => 'string',
                'label'       => 'Kyuubi API Base URL',
                'description' => 'Base URL for Kyuubi Disbursement API',
                'is_public'   => 0,
                'is_editable' => 1,
            ],
            [
                'group'       => 'kyuubi',
                'key'         => 'catalogue_api_base_url',
                'value'       => 'https://kyuubi-external-catalogue-staging.voyagerapis.com',
                'type'        => 'string',
                'label'       => 'Kyuubi Catalogue API Base URL',
                'description' => 'Base URL for Kyuubi Catalogue API',
                'is_public'   => 0,
                'is_editable' => 1,
            ],
            [
                'group'       => 'kyuubi',
                'key'         => 'api_timeout_seconds',
                'value'       => '30',
                'type'        => 'integer',
                'label'       => 'API Timeout',
                'description' => 'Timeout in seconds for Kyuubi API requests',
                'is_public'   => 0,
                'is_editable' => 1,
            ],

            // Gift Settings
            [
                'group'       => 'gift',
                'key'         => 'gift_expiry_days',
                'value'       => '30',
                'type'        => 'integer',
                'label'       => 'Gift Expiry Days',
                'description' => 'Days before an unclaimed gift expires',
                'is_public'   => 1,
                'is_editable' => 1,
            ],
            [
                'group'       => 'gift',
                'key'         => 'gift_points_bonus',
                'value'       => '50',
                'type'        => 'integer',
                'label'       => 'Gift Points Bonus',
                'description' => 'Bonus points for sending a gift',
                'is_public'   => 1,
                'is_editable' => 1,
            ],

            // Active User Definition
            [
                'group'       => 'user',
                'key'         => 'active_user_months',
                'value'       => '12',
                'type'        => 'integer',
                'label'       => 'Active User Months',
                'description' => 'Months of activity to be considered an active user',
                'is_public'   => 0,
                'is_editable' => 1,
            ],
        ];

        $this->db->table('settings')->insertBatch($data);
    }
}