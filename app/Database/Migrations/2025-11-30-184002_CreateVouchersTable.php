<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVoucherSystem extends Migration
{
    public function up()
    {
        // 1. Campaigns (The Rules)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT', 'null' => true],
            
            // Logic Types
            'code_type' => ['type' => 'ENUM', 'constraint' => ['universal', 'unique_batch']],
            'discount_type' => ['type' => 'ENUM', 'constraint' => ['fixed_amount', 'percentage']],
            'discount_value' => ['type' => 'DECIMAL', 'constraint' => '10,2'], // e.g., 100.00 or 0.15 (15%)
            
            // Constraints
            'min_spend_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'max_discount_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true], // Cap for % discounts
            'usage_limit_per_user' => ['type' => 'INT', 'default' => 1],
            'total_usage_limit' => ['type' => 'INT', 'null' => true], // Null = infinite
            
            // Strategic Controls
            'is_stackable' => ['type' => 'BOOLEAN', 'default' => false], // Can be used with other vouchers?
            'start_date' => ['type' => 'DATETIME'],
            'end_date' => ['type' => 'DATETIME'],
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('voucher_campaigns');

        // 2. Applicability (Which products does this apply to?)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'campaign_id' => ['type' => 'INT', 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('campaign_id', 'voucher_campaigns', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('voucher_applicability');

        // 3. Codes (The actual input strings)
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'campaign_id' => ['type' => 'INT', 'unsigned' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 50], // The "WELCOME20" or "XCY-999"
            'is_redeemed' => ['type' => 'BOOLEAN', 'default' => false], // For unique codes
            'redeemed_at' => ['type' => 'DATETIME', 'null' => true],
            'redeemed_by_user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code'); // Ensure no duplicate codes globally
        $this->forge->addForeignKey('campaign_id', 'voucher_campaigns', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('voucher_codes');
    }

    public function down()
    {
        $this->forge->dropTable('voucher_codes');
        $this->forge->dropTable('voucher_applicability');
        $this->forge->dropTable('voucher_campaigns');
    }
}