<?php

namespace App\Models;

use CodeIgniter\Model;

class TopBannerModel extends Model
{
    protected $table            = 'top_banner';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'text', 
        'icon',
        'is_active'
    ];

    protected $useTimestamps = true;

    /**
     * Get the active banner
     */
    public function getActiveBanner()
    {
        return $this->where('is_active', 1)->first();
    }
}
