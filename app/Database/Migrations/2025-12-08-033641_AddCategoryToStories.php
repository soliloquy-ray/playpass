<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryToStories extends Migration
{
    public function up()
    {
        $this->forge->addColumn('stories', [
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['story', 'promo', 'event', 'trailer'],
                'default'    => 'story',
                'after'      => 'slug'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('stories', 'category');
    }
}