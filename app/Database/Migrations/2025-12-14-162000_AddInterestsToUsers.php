<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInterestsToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'interests' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'User interests/preferences (array of strings)'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'interests');
    }
}
