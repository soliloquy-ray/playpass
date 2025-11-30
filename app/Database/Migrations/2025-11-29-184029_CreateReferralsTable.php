<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateReferralsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'referral_code_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'referrer_user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'referee_user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'completed', 'expired', 'invalid'],
                'default'    => 'pending',
            ],
            // Order that completed the referral
            'qualifying_order_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Points awarded
            'referrer_points' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'referee_points' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'referrer_points_awarded_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'referee_points_awarded_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('referral_code_id');
        $this->forge->addKey('referrer_user_id');
        $this->forge->addKey('referee_user_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('referral_code_id', 'referral_codes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('referrer_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('referee_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('qualifying_order_id', 'orders', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('referrals', true);
    }

    public function down()
    {
        $this->forge->dropTable('referrals', true);
    }
}