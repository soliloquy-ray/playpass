<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProducts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'sku' => ['type' => 'VARCHAR', 'constraint' => 50],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            
            // Visuals (Added these)
            'thumbnail_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            // Integration Fields
            'maya_product_code' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true], 
            
            // Promotion Rules
            'points_to_earn' => ['type' => 'INT', 'default' => 0],
            'is_bundle'      => ['type' => 'BOOLEAN', 'default' => false],
            'is_featured'    => ['type' => 'BOOLEAN', 'default' => false], // Added this
            'is_active'      => ['type' => 'BOOLEAN', 'default' => true],
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('sku');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}