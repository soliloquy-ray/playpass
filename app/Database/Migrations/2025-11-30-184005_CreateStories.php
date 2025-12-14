<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 255],
            
            // New Visual Fields
            'image'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // The thumbnail
            'is_trailer'  => ['type' => 'BOOLEAN', 'default' => false], // Triggers the "TRAILER" badge
            
            // Content
            'excerpt'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // Short summary for the card
            'content'     => ['type' => 'LONGTEXT', 'null' => true], // Full article
            
            'status'      => ['type' => 'ENUM', 'constraint' => ['draft', 'published'], 'default' => 'draft'],
            'published_at'=> ['type' => 'DATETIME', 'null' => true], // To show "10:00 AM"
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('stories');
    }

    public function down()
    {
        $this->forge->dropTable('stories');
    }
}