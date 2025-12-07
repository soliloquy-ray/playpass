<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersAndPoints extends Migration
{
    public function up()
    {
        // 1. Users Table
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'first_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'role' => ['type' => 'ENUM', 'constraint' => ['admin', 'customer'], 'default' => 'customer'],
            
            // Referral System
            'my_referral_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'referred_by_user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            
            // Cached Balance (for performance, but truth source is Ledger)
            'current_points_balance' => ['type' => 'INT', 'default' => 0],
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('my_referral_code');
        $this->forge->createTable('users');

        // 2. Point Ledger (The "Bank Statement" for points)
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'amount' => ['type' => 'INT', 'constraint' => 11], // Positive for earn, negative for spend
            'transaction_type' => ['type' => 'ENUM', 'constraint' => ['purchase_reward', 'redemption', 'referral_bonus', 'adjustment']], 
            'reference_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true], // Order ID or Admin Note
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('point_ledger');
    }

    public function down()
    {
        $this->forge->dropTable('point_ledger');
        $this->forge->dropTable('users');
    }
}