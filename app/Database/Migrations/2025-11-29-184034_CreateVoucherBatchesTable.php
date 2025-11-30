<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateVoucherBatchesTable extends Migration
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
                'constraint' => ['universal', 'unique'],
                'default'    => 'unique',
            ],
            // For universal vouchers, the single code
            'universal_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            // Discount type
            'discount_type' => [
                'type'       => 'ENUM',
                'constraint' => ['fixed', 'percentage'],
                'default'    => 'fixed',
            ],
            'discount_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
            ],
            // Max discount for percentage vouchers
            'max_discount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'null'       => true,
            ],
            // Minimum purchase requirement
            'min_purchase_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'default'    => 0.00,
            ],
            // Usage limits
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
            // Validity period
            'starts_at' => [
                'type' => 'DATETIME',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            // Applicability
            'applies_to' => [
                'type'       => 'ENUM',
                'constraint' => ['all', 'specific_products', 'specific_categories', 'specific_providers'],
                'default'    => 'all',
            ],
            // Stackable with other promotions
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
        $this->forge->addKey('universal_code');
        $this->forge->addKey('type');
        $this->forge->addKey('is_active');
        $this->forge->addKey('starts_at');
        $this->forge->addKey('expires_at');
        $this->forge->addKey('deleted_at');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('voucher_batches', true);
    }

    public function down()
    {
        $this->forge->dropTable('voucher_batches', true);
    }
}