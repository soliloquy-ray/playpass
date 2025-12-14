<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'uuid' => ['type' => 'VARCHAR', 'constraint' => 36, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'first_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'role' => ['type' => 'ENUM', 'constraint' => ['admin', 'super_admin'], 'default' => 'admin'],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'suspended'], 'default' => 'active'],
            'avatar_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'last_login_at' => ['type' => 'DATETIME', 'null' => true],
            'last_activity_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('uuid');
        $this->forge->createTable('admins');
    }

    public function down()
    {
        $this->forge->dropTable('admins');
    }
}

