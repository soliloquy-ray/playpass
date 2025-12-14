<?php

namespace App\Models;

use CodeIgniter\Model;

class CarouselSlideModel extends Model
{
    protected $table            = 'carousel_slides';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'title', 
        'subtitle', 
        'cta_text', 
        'cta_link',
        'image_url',
        'bg_gradient_start',
        'bg_gradient_end',
        'sort_order',
        'is_active'
    ];

    protected $useTimestamps = true;

    /**
     * Get all active slides ordered by sort_order
     */
    public function getActiveSlides()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}

