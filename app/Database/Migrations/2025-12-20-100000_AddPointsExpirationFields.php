<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Add expiration tracking to point_ledger and snapshot points in order_items.
 * 
 * point_ledger changes:
 * - earned_at: When points were earned (for expiration calculation)
 * - expires_at: When points expire (1 year from earning)
 * - notes: For adjustment records (requirement #3)
 * 
 * order_items changes:
 * - points_earned_at_purchase: Snapshot of product's points at purchase time
 */
class AddPointsExpirationFields extends Migration
{
    public function up()
    {
        // Add fields to point_ledger
        $this->forge->addColumn('point_ledger', [
            'earned_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'reference_id'
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'earned_at'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'expires_at'
            ],
        ]);

        // Add index for expiration queries
        $db = \Config\Database::connect();
        $db->query('CREATE INDEX idx_point_ledger_expires_at ON point_ledger(expires_at)');
        $db->query('CREATE INDEX idx_point_ledger_user_expires ON point_ledger(user_id, expires_at)');

        // Add points snapshot to order_items
        $this->forge->addColumn('order_items', [
            'points_earned_at_purchase' => [
                'type' => 'INT',
                'default' => 0,
                'after' => 'total'
            ],
        ]);
    }

    public function down()
    {
        // Remove from point_ledger
        $this->forge->dropColumn('point_ledger', ['earned_at', 'expires_at', 'notes']);
        
        // Drop indexes
        $db = \Config\Database::connect();
        $db->query('DROP INDEX IF EXISTS idx_point_ledger_expires_at ON point_ledger');
        $db->query('DROP INDEX IF EXISTS idx_point_ledger_user_expires ON point_ledger');

        // Remove from order_items
        $this->forge->dropColumn('order_items', ['points_earned_at_purchase']);
    }
}
