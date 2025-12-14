<?php

namespace App\Models;

use CodeIgniter\Model;

class PromosModel extends Model
{
    protected $table            = 'promos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'name', 
        'description', 
        'icon', 
        'start_date', 
        'end_date', 
        'is_active'
    ];

    protected $useTimestamps    = true;

    /**
     * Fetch all active promos that are currently valid based on dates.
     */
    public function getActivePromos()
    {
        $currentDate = date('Y-m-d H:i:s');

        return $this->where('is_active', 1)
                    // Start date is null OR in the past
                    ->groupStart()
                        ->where('start_date', null)
                        ->orWhere('start_date <=', $currentDate)
                    ->groupEnd()
                    // End date is null OR in the future
                    ->groupStart()
                        ->where('end_date', null)
                        ->orWhere('end_date >=', $currentDate)
                    ->groupEnd()
                    ->findAll();
    }
}