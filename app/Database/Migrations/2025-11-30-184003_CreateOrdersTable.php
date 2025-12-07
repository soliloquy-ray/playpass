<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrders extends Migration
{
    public function up()
    {
        // 1. Orders Header
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            
            // Money Fields
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'discount_total' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'points_redeemed_value' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'grand_total' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            
            // Statuses
            'payment_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'paid', 'failed', 'refunded'], 'default' => 'pending'],
            'fulfillment_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'sent', 'failed'], 'default' => 'pending'],
            
            // Maya Integration
            'maya_checkout_id' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'maya_reference_number' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');

        // 2. Order Items
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'order_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
            'quantity' => ['type' => 'INT', 'default' => 1],
            'price_at_purchase' => ['type' => 'DECIMAL', 'constraint' => '10,2'], // Snapshot price
            'total' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_items');

        // 3. Track which vouchers were used on this order
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'order_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'voucher_code_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'discount_amount_applied' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('voucher_code_id', 'voucher_codes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_applied_vouchers');
    }

    public function down()
    {
        $this->forge->dropTable('order_applied_vouchers');
        $this->forge->dropTable('order_items');
        $this->forge->dropTable('orders');
    }
}