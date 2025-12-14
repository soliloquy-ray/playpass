<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // FIXED: Set this to false because your 'products' table doesn't have a 'deleted_at' column
    protected $useSoftDeletes   = false; 
    
    protected $allowedFields    = [
        'sku', 
        'name', 
        'description', 
        'price', 
        'thumbnail_url',
        'is_featured',
        'maya_product_code', 
        'points_to_earn', 
        'is_bundle', 
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Fetch products with filters and pagination
     */
    public function getFilteredProducts($filters = [], int $limit = 9, int $offset = 0)
    {
        $builder = $this->select('products.*, brands.name as brand_name, brands.logo as brand_logo')
                        ->join('brands', 'brands.id = products.brand_id')
                        ->where('products.is_active', 1);

        // 1. Brand Filter
        if (!empty($filters['brand'])) {
            $builder->where('brands.id', $filters['brand']);
        }

        // 2. Price Filter (Ranges)
        if (!empty($filters['price'])) {
            switch ($filters['price']) {
                case 'low':   $builder->where('price <', 50); break;
                case 'mid':   $builder->where('price >=', 50)->where('price <=', 100); break;
                case 'high':  $builder->where('price >', 100); break;
            }
        }

        // 3. Duration Filter (Loose matching on name/SKU since we don't have a duration column)
        if (!empty($filters['duration'])) {
            // Looks for "3 Day", "7 Day", "30 Day" in the name
            $builder->like('products.name', $filters['duration']);
        }

        return $builder->orderBy('products.sort_order', 'ASC')
                       ->findAll($limit, $offset);
    }
}