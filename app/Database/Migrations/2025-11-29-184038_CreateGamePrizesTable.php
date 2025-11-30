<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateGamePrizesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'game_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
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
                'constraint' => ['points', 'voucher', 'product', 'nothing'],
                'default'    => 'nothing',
            ],
            // For points prizes
            'points_value' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            // For voucher prizes - link to voucher batch
            'voucher_batch_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // For product prizes
            'product_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Probability (0-100, can have decimals)
            'probability' => [
                'type'       => 'DECIMAL',
                'constraint' => '6,3',
                'unsigned'   => true,
                'default'    => 0.000,
            ],
            // Stock limits
            'total_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'awarded_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            // Display
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'display_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'null'       => true,
            ],
            'sort_order' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('game_id');
        $this->forge->addKey('type');
        $this->forge->addKey('is_active');
        $this->forge->addForeignKey('game_id', 'games', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('voucher_batch_id', 'voucher_batches', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('game_prizes', true);
    }

    public function down()
    {
        $this->forge->dropTable('game_prizes', true);
    }
}