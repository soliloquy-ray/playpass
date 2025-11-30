<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePromosTable extends Migration
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
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['first_purchase', 'amount_threshold', 'product_specific', 'category_specific', 'general'],
                'default'    => 'general',
            ],
            // Discount
            'discount_type' => [
                'type'       => 'ENUM',
                'constraint' => ['fixed', 'percentage', 'points'],
                'default'    => 'fixed',
            ],
            'discount_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
            ],
            'max_discount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'null'       => true,
            ],
            // Requirements
            'min_purchase_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'default'    => 0.00,
            ],
            'min_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 1,
            ],
            // Applicability
            'applies_to' => [
                'type'       => 'ENUM',
                'constraint' => ['all', 'specific_products', 'specific_categories', 'specific_providers'],
                'default'    => 'all',
            ],
            // Usage limits
            'total_usage_limit' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'usage_count' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'limit_per_user' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Validity
            'starts_at' => [
                'type' => 'DATETIME',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            // Flags
            'is_stackable' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
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
            // Display
            'banner_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'terms_conditions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // $this->forge->addKey('uuid');
        // $this->forge->addKey('code');
        $this->forge->addKey('type');
        $this->forge->addKey('is_active');
        $this->forge->addKey('starts_at');
        $this->forge->addKey('expires_at');
        $this->forge->addKey('deleted_at');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('promos', true);
    }

    public function down()
    {
        $this->forge->dropTable('promos', true);
    }
}