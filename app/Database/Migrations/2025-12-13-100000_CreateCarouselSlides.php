<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCarouselSlides extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            
            // Slide Content
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'subtitle' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            // Call-to-Action
            'cta_text' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // e.g., "Explore Now"
            'cta_link' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // e.g., "/products"
            
            // Visuals
            'image_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bg_gradient_start' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#d8369f'],
            'bg_gradient_end' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#051429'],
            
            // Logic
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('carousel_slides');
    }

    public function down()
    {
        $this->forge->dropTable('carousel_slides');
    }
}

