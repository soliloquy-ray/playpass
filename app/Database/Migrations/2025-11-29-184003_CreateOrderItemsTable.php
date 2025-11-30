<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'product_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            // Price snapshot at time of order
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
            ],
            'discount_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'default'    => 0.00,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
            ],
            // Product snapshot
            'product_sku' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'product_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            // Kyuubi product code
            'kyuubi_product_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            // Fulfillment status per item
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'processing', 'fulfilled', 'failed', 'refunded'],
                'default'    => 'pending',
            ],
            // Points earned for this item
            'points_earned' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            // Bundle info
            'bundle_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'is_bundle_item' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_bundle_freebie' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            // For gaming products
            'recipient_info' => [
                'type' => 'JSON',
                'null' => true,
            ],
            // Fulfilled PIN codes (encrypted, JSON array)
            'fulfilled_pins' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'fulfilled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            // Error tracking
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'retry_count' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('product_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('order_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('order_items', true);
    }
}