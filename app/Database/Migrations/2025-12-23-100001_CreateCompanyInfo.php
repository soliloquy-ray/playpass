<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration to create the company_info table for storing
 * editable company details like address, phone, email, copyright.
 */
class CreateCompanyInfo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('company_info');
    }

    public function down()
    {
        $this->forge->dropTable('company_info');
    }
}
