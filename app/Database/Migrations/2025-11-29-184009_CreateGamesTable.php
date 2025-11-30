<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateGamesTable extends Migration
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
                'unique'     => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['spin_and_win', 'slot_machine'],
            ],
            // Theme selection
            'theme' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'default',
            ],
            // Frequency limits
            'play_frequency' => [
                'type'       => 'ENUM',
                'constraint' => ['once_per_hour', 'once_per_day', 'once_per_week', 'unlimited'],
                'default'    => 'once_per_day',
            ],
            'max_plays_per_user' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Requirements
            'requires_purchase' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'min_purchase_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
                'default'    => 0.00,
            ],
            // Display
            'banner_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'background_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            // Game config (colors, sounds, etc.)
            'config' => [
                'type' => 'JSON',
                'null' => true,
            ],
            // Validity
            'starts_at' => [
                'type' => 'DATETIME',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            // Stats
            'total_plays' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'total_prizes_awarded' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'created_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('type');
        $this->forge->addKey('is_active');
        $this->forge->addKey('starts_at');
        $this->forge->addKey('expires_at');
        $this->forge->addKey('deleted_at');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('games', true);
    }

    public function down()
    {
        $this->forge->dropTable('games', true);
    }
}