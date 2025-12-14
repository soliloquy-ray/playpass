<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSocialAuthToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'google_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'password_hash'
            ],
            'facebook_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'google_id'
            ],
            'avatar_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
                'after' => 'facebook_id'
            ],
        ]);

        // Add indexes for faster lookups
        $this->forge->addKey('google_id', false, false, 'idx_google_id');
        $this->forge->addKey('facebook_id', false, false, 'idx_facebook_id');
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['google_id', 'facebook_id', 'avatar_url']);
    }
}

