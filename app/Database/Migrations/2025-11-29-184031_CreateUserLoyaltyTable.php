<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUserLoyaltyTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'unique'   => true,
            ],
            'tier_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            // Current stats
            'total_purchases' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'total_amount_spent' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'unsigned'   => true,
                'default'    => 0.00,
            ],
            'total_referrals' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            // Points balance
            'points_balance' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'points_lifetime_earned' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'points_lifetime_redeemed' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'points_lifetime_expired' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            // Streak tracking
            'current_streak' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'longest_streak' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'last_purchase_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            // Tier changes
            'tier_achieved_at' => [
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
        // $this->forge->addKey('user_id');
        $this->forge->addKey('tier_id');
        $this->forge->addKey('points_balance');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tier_id', 'loyalty_tiers', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('user_loyalty', true);
    }

    public function down()
    {
        $this->forge->dropTable('user_loyalty', true);
    }
}