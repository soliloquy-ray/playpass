<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePointTransactionsTable extends Migration
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
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['earn', 'redeem', 'expire', 'adjust', 'bonus', 'refund'],
            ],
            // Positive for earn, negative for redeem/expire
            'points' => [
                'type' => 'INT',
            ],
            // Balance after this transaction
            'balance_after' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            // Source of points
            'source' => [
                'type'       => 'ENUM',
                'constraint' => ['purchase', 'referral', 'birthday', 'promo', 'game', 'admin', 'redemption', 'expiry', 'refund'],
            ],
            // Reference to the source record
            'reference_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'reference_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // For orders
            'order_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'order_item_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Description
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Expiry tracking (for earned points)
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expired_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            // Points remaining from this earn transaction
            'points_remaining' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Admin who made adjustment
            'created_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('type');
        $this->forge->addKey('source');
        $this->forge->addKey('order_id');
        $this->forge->addKey('expires_at');
        $this->forge->addKey('created_at');
        $this->forge->addKey(['user_id', 'type']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('order_item_id', 'order_items', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('point_transactions', true);
    }

    public function down()
    {
        $this->forge->dropTable('point_transactions', true);
    }
}