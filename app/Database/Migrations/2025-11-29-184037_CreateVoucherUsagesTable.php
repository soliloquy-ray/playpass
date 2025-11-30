<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateVoucherUsagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'batch_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'voucher_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'order_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'discount_applied' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'unsigned'   => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('batch_id');
        $this->forge->addKey('voucher_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('order_id');
        $this->forge->addKey(['batch_id', 'user_id']);
        $this->forge->addForeignKey('batch_id', 'voucher_batches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('voucher_id', 'vouchers', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('voucher_usages', true);
    }

    public function down()
    {
        $this->forge->dropTable('voucher_usages', true);
    }
}