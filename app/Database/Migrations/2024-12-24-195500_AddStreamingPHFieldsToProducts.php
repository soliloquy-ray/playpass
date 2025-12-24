<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStreamingPHFieldsToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'streamingph_product_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'maya_product_code'
            ],
            'streamingph_validity' => [
                'type'    => 'INT',
                'null'    => true,
                'after'   => 'streamingph_product_id'
            ],
            'streamingph_balance' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'streamingph_validity'
            ],
        ]);
        
        // Note: To add an index on existing table, you'd need to use $this->db->query() 
        // or add a separate index migration. For now, the column is added without index.
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['streamingph_product_id', 'streamingph_validity', 'streamingph_balance']);
    }
}
