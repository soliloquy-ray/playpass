<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateBundlesTable extends Migration
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
                'constraint' => ['buy_x_get_y', 'buy_x_get_points', 'fixed_bundle'],
                'default'    => 'fixed_bundle',
            ],
            // Pricing
            'bundle_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'null'       => true,
            ],
            'discount_type' => [
                'type'       => 'ENUM',
                'constraint' => ['fixed', 'percentage', 'none'],
                'default'    => 'none',
            ],
            'discount_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'default'    => 0.00,
            ],
            // Points reward
            'bonus_points' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            // Requirements
            'min_purchase_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'default'    => 0.00,
            ],
            // Stock/Usage limits
            'total_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'used_quantity' => [
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
            // Display
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'sort_order' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
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
        $this->forge->addKey('type');
        $this->forge->addKey('is_active');
        $this->forge->addKey('starts_at');
        $this->forge->addKey('expires_at');
        $this->forge->addKey('deleted_at');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('bundles', true);
    }

    public function down()
    {
        $this->forge->dropTable('bundles', true);
    }
}