<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder to create initial company information from current footer
 */
class CompanyInfoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'key'        => 'address',
                'value'      => "4F PDI Bldg., 1098 Chino Roces Ave. cor Yague and Mascardo\nSts. 1204, Makati, Metro Manila, Philippines",
                'label'      => 'Company Address',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key'        => 'phone',
                'value'      => '02 7623 5639',
                'label'      => 'Phone Number',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key'        => 'email',
                'value'      => 'support@playpass.ph',
                'label'      => 'Email Address',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'key'        => 'copyright',
                'value'      => '© Copyright 2024 Megamobile, Inc. All Rights Reserved',
                'label'      => 'Copyright Text',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('company_info')->insertBatch($data);
    }
}
