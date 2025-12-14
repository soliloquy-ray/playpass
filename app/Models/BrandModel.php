<?php

namespace App\Models;

use CodeIgniter\Model;

class BrandModel extends Model
{
    protected $table            = 'brands';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Fields we can insert/update
    protected $allowedFields    = ['name', 'logo', 'is_enabled'];

    protected $useTimestamps    = true;
}