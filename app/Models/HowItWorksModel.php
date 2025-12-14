<?php

namespace App\Models;

use CodeIgniter\Model;

class HowItWorksModel extends Model
{
    protected $table            = 'how_it_works';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'title', 
        'description', 
        'icon',
        'sort_order',
        'is_active'
    ];

    protected $useTimestamps = true;

    /**
     * Get all active steps ordered by sort_order
     */
    public function getActiveSteps()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}
