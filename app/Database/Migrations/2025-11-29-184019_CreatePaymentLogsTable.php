<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePaymentLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'payment_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'order_item_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            // Kyuubi endpoint called
            'endpoint' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'method' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            // Request/Response logging
            'request_payload' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'response_payload' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'http_status_code' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Status tracking
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['success', 'error', 'timeout', 'pending'],
            ],
            // Timing
            'response_time_ms' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            // Error details
            'error_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('payment_id');
        $this->forge->addKey('order_item_id');
        $this->forge->addKey('action');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('payment_id', 'payments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_item_id', 'order_items', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('payment_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('payment_logs', true);
    }
}