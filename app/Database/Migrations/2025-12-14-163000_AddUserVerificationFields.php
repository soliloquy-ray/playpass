<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserVerificationFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'email'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'active', 'inactive', 'suspended'],
                'default' => 'pending',
                'after' => 'role'
            ],
            'email_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status'
            ],
            'email_verification_token' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'email_verified_at'
            ],
            'email_verification_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'email_verification_token'
            ],
            'phone_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'email_verification_expires_at'
            ],
            'last_login_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'phone_verified_at'
            ],
            'last_activity_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'last_login_at'
            ],
            'uuid' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => true,
                'after' => 'id'
            ]
        ]);

        // Add index for verification token lookups
        $this->forge->addKey('email_verification_token', false, false, 'idx_verification_token');
        $this->forge->addKey('status', false, false, 'idx_status');
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'phone',
            'status',
            'email_verified_at',
            'email_verification_token',
            'email_verification_expires_at',
            'phone_verified_at',
            'last_login_at',
            'last_activity_at',
            'uuid'
        ]);
    }
}
