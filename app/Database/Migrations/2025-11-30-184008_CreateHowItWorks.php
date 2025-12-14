<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHowItWorks extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            
            // The main heading, e.g., "Select Product"
            'title' => ['type' => 'VARCHAR', 'constraint' => 100],
            
            // The explanation text
            'description' => ['type' => 'TEXT'],
            
            // Icon path or class (e.g., 'fa-smile' or '/uploads/step1.png')
            'icon' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            // Critical for controlling the 1, 2, 3, 4, 5 sequence
            'sort_order' => ['type' => 'INT', 'default' => 0],
            
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('how_it_works');
    }

    public function down()
    {
        $this->forge->dropTable('how_it_works');
    }
}