<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTopBanner extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            
            // Banner Content
            'text' => ['type' => 'VARCHAR', 'constraint' => 255],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // e.g., "fa-bolt"
            
            // Logic
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('top_banner');
    }

    public function down()
    {
        $this->forge->dropTable('top_banner');
    }
}
