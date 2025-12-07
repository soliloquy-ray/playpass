<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionLogs extends Migration
{
    public function up()
    {
        // 1. The "Price Reducers" Table
        // Tracks EXACTLY what reduced the price (Voucher, Bundle, Promo)
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'order_id' => ['type' => 'BIGINT', 'unsigned' => true],
            
            // What kind of reduction was this?
            'adjustment_type' => ['type' => 'ENUM', 'constraint' => ['voucher', 'bundle_discount', 'promo_rule', 'referral_credit']],
            
            // The identifier (e.g., "WELCOME20", "BUNDLE_GAMER_PACK", "REF-JM-123")
            'reference_code' => ['type' => 'VARCHAR', 'constraint' => 100],
            
            // How much was taken off?
            'amount_deducted' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            
            // Human readable log for Admin Reports
            'description' => ['type' => 'VARCHAR', 'constraint' => 255], 
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_price_adjustments');
    }

    public function down()
    {
        $this->forge->dropTable('order_price_adjustments');
    }
}