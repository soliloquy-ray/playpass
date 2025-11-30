<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateGamePlaysTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                // 'unique'     => true,
            ],
            'game_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'prize_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Result
            'won' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            // Prize details snapshot
            'prize_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'prize_value' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            // For voucher prizes
            'voucher_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // For point transaction
            'point_transaction_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Game result data (spin position, slot result, etc.)
            'result_data' => [
                'type' => 'JSON',
                'null' => true,
            ],
            // Tracking
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'played_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('uuid');
        $this->forge->addKey('game_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('prize_id');
        $this->forge->addKey('won');
        $this->forge->addKey('played_at');
        $this->forge->addKey(['game_id', 'user_id']);
        $this->forge->addForeignKey('game_id', 'games', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('prize_id', 'game_prizes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('voucher_id', 'vouchers', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('point_transaction_id', 'point_transactions', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('game_plays', true);
    }

    public function down()
    {
        $this->forge->dropTable('game_plays', true);
    }
}