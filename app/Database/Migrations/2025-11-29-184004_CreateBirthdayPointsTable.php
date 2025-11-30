<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateBirthdayPointsTable extends Migration
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
            ],
            'year' => [
                'type'     => 'YEAR',
            ],
            'points_awarded' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'point_transaction_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'year']);
        $this->forge->addKey('expires_at');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('point_transaction_id', 'point_transactions', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('birthday_points', true);
    }

    public function down()
    {
        $this->forge->dropTable('birthday_points', true);
    }
}