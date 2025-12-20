<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * First Purchase Promo System
 * 
 * Creates tables for managing first-purchase discounts:
 * - first_purchase_promos: Settings for the promo
 * - first_purchase_promo_products: Applicable products
 * - users: Add has_completed_first_purchase flag
 */
class CreateFirstPurchasePromos extends Migration
{
    public function up()
    {
        // 1. First Purchase Promos Settings
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'label' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            
            // Discount settings
            'discount_type' => ['type' => 'ENUM', 'constraint' => ['fixed_amount', 'percentage'], 'default' => 'fixed_amount'],
            'discount_value' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'min_spend_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'max_discount_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true], // Cap for percentage
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('first_purchase_promos');

        // 2. Product Applicability (which products qualify)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'promo_id' => ['type' => 'INT', 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('promo_id', 'first_purchase_promos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('first_purchase_promo_products');

        // 3. Add flag to users table
        $this->forge->addColumn('users', [
            'has_completed_first_purchase' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'current_points_balance'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('first_purchase_promo_products');
        $this->forge->dropTable('first_purchase_promos');
        $this->forge->dropColumn('users', ['has_completed_first_purchase']);
    }
}
