<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGiftFieldsToOrders extends Migration
{
    public function up()
    {
        $fields = [
            'recipient_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'maya_reference_number',
            ],
            'gift_message' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'recipient_email',
            ],
        ];
        
        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', ['recipient_email', 'gift_message']);
    }
}

