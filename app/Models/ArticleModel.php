<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    protected $table            = 'cms_articles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['title', 'slug', 'content', 'status', 'author_id'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Scope to get only published articles
     */
    public function getPublished(int $limit = 3)
    {
        return $this->where('status', 'published')
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }
}