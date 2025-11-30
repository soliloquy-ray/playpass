<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateGiftsTable extends Migration
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
            'order_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'order_item_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'sender_user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            // Recipient info
            'recipient_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'recipient_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'gift_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Claim info
            'claim_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                // 'unique'     => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'sent', 'claimed', 'expired', 'cancelled'],
                'default'    => 'pending',
            ],
            'recipient_user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Email tracking
            'sender_notified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'recipient_notified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'claimed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires_at' => [
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
        $this->forge->addKey('uuid');
        $this->forge->addKey('claim_code');
        $this->forge->addKey('order_id');
        $this->forge->addKey('sender_user_id');
        $this->forge->addKey('recipient_email');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_item_id', 'order_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sender_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('recipient_user_id', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('gifts', true);
    }

    public function down()
    {
        $this->forge->dropTable('gifts', true);
    }
}