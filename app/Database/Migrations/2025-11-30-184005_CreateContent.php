<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCmsArticles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 255], // URL friendly: 'how-to-redeem'
            'content'     => ['type' => 'LONGTEXT'],
            'status'      => ['type' => 'ENUM', 'constraint' => ['draft', 'published'], 'default' => 'draft'],
            'author_id'   => ['type' => 'INT', 'unsigned' => true],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('cms_articles');
    }

    public function down()
    {
        $this->forge->dropTable('cms_articles');
    }
}