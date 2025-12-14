<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomerSupport extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            
            // Button Label (e.g., "Email", "Viber", "Lazada")
            'label' => ['type' => 'VARCHAR', 'constraint' => 100],
            
            // The destination (e.g., "mailto:support@playpass.ph", "https://viber.com/...")
            'link' => ['type' => 'VARCHAR', 'constraint' => 255],
            
            // Icon path or class
            'icon' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            // To arrange them in the specific grid order you want
            'sort_order' => ['type' => 'INT', 'default' => 0],
            
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('customer_support');
    }

    public function down()
    {
        $this->forge->dropTable('customer_support');
    }
}