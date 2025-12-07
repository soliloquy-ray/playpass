<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBundleItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'parent_product_id' => ['type' => 'INT', 'unsigned' => true], // The "Bundle"
            'child_product_id'  => ['type' => 'INT', 'unsigned' => true], // The actual item
            'quantity'          => ['type' => 'INT', 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        // Cascading delete: If you delete the product, the bundle definition dies too
        $this->forge->addForeignKey('parent_product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('child_product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bundle_items');
    }

    public function down()
    {
        $this->forge->dropTable('bundle_items');
    }
}