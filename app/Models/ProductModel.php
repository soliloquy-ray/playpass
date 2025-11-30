<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    
    protected $allowedFields    = [
        'uuid', 'category_id', 'provider_id', 'sku', 'kyuubi_product_code',
        'name', 'short_description', 'description', 'type',
        'price', 'original_price', 'cost_price',
        'stock_type', 'stock_quantity', 'low_stock_threshold',
        'points_earned', 'points_multiplier',
        'image_url', 'thumbnail_url', 'sort_order',
        'is_active', 'is_featured',
        'available_from', 'available_until',
        'min_quantity', 'max_quantity', 'max_per_user',
        'meta_title', 'meta_description', 'slug', 'metadata'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks
    protected $beforeInsert = ['generateUUID', 'setSlug'];

    protected function generateUUID(array $data)
    {
        $data['data']['uuid'] = (string) \Ramsey\Uuid\Uuid::uuid4();
        return $data;
    }

    protected function setSlug(array $data)
    {
        if (empty($data['data']['slug']) && !empty($data['data']['name'])) {
            $data['data']['slug'] = url_title($data['data']['name'], '-', true);
        }
        return $data;
    }

    // Custom Method: Get only active and available products
    public function getActiveProducts()
    {
        $now = date('Y-m-d H:i:s');
        return $this->where('is_active', 1)
                    ->groupStart() // (available_from IS NULL OR <= NOW)
                        ->where('available_from', null)
                        ->orWhere('available_from <=', $now)
                    ->groupEnd()
                    ->groupStart() // (available_until IS NULL OR >= NOW)
                        ->where('available_until', null)
                        ->orWhere('available_until >=', $now)
                    ->groupEnd()
                    ->findAll();
    }
}