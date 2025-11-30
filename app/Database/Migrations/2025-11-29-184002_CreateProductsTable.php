<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'unique'     => true,
            ],
            'category_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'provider_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'sku' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            // Kyuubi product code for disbursement
            'kyuubi_product_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'short_description' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['epin', 'load', 'game_credit', 'voucher', 'other'],
                'default'    => 'epin',
            ],
            // Pricing
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
            ],
            'original_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'null'       => true,
            ],
            'cost_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'null'       => true,
            ],
            // Stock management
            'stock_type' => [
                'type'       => 'ENUM',
                'constraint' => ['unlimited', 'limited', 'inventory'],
                'default'    => 'unlimited',
            ],
            'stock_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'low_stock_threshold' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 10,
            ],
            // Points configuration
            'points_earned' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'points_multiplier' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 1.00,
            ],
            // Images
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'thumbnail_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            // Display
            'sort_order' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            // Validity period
            'available_from' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'available_until' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            // Purchase limits
            'min_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 1,
            ],
            'max_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'max_per_user' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            // SEO
            'meta_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'meta_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            // Additional data
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('type');
        $this->forge->addKey('is_active');
        $this->forge->addKey('is_featured');
        $this->forge->addKey('sort_order');
        $this->forge->addKey('available_from');
        $this->forge->addKey('available_until');
        $this->forge->addKey('deleted_at');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('provider_id', 'providers', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('products', true);
    }

    public function down()
    {
        $this->forge->dropTable('products', true);
    }
}