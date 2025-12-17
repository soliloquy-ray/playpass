<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnhanceVoucherSystem extends Migration
{
    public function up()
    {
        // Add label field to voucher_campaigns
        $fields = [
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'name',
            ],
        ];
        $this->forge->addColumn('voucher_campaigns', $fields);

        // Create table to track per-user voucher usage for limit enforcement
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'campaign_id' => ['type' => 'INT', 'unsigned' => true],
            'voucher_code_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'order_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'used_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'campaign_id']);
        $this->forge->addKey('voucher_code_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('campaign_id', 'voucher_campaigns', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('voucher_code_id', 'voucher_codes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('voucher_usage_log');
    }

    public function down()
    {
        $this->forge->dropTable('voucher_usage_log');
        $this->forge->dropColumn('voucher_campaigns', 'label');
    }
}

