<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerSupportModel extends Model
{
    protected $table            = 'customer_support';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['label', 'link', 'icon', 'sort_order', 'is_active'];

    public function getChannels()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}