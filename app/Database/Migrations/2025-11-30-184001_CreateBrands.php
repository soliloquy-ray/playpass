<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBrands extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255, // URL or file path
                'null'       => true,
            ],
            'is_enabled' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('brands');
    }

    public function down()
    {
        // Disable foreign key checks to allow dropping referenced table
        $this->forge->dropTable('brands');
    }
}