<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateProductInventoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            // The actual PIN/code value (encrypted)
            'pin_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
            ],
            // Serial number if applicable
            'serial_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'reserved', 'sold', 'expired', 'voided'],
                'default'    => 'available',
            ],
            // Validity dates for the PIN itself
            'valid_from' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'valid_until' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            // Batch info for upload tracking
            'batch_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            // Tracking
            'reserved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'reserved_by_user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'reserved_until' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'sold_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'order_item_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
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
        $this->forge->addKey('product_id');
        $this->forge->addKey('status');
        $this->forge->addKey('batch_reference');
        $this->forge->addKey('valid_until');
        $this->forge->addKey('reserved_until');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('product_inventory', true);
    }

    public function down()
    {
        $this->forge->dropTable('product_inventory', true);
    }
}