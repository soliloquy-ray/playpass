<?php

namespace App\Models;

use CodeIgniter\Model;

class StoryModel extends Model
{
    protected $table = 'stories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'title', 
        'slug', 
        'category',
        'image', 
        'is_trailer', 
        'excerpt', 
        'content', 
        'status',      // 'draft' or 'published'
        'published_at',
    ];

    protected $useTimestamps = true;

    /**
     * Fetch stories for the infinite scroll API
     */
    public function getStories(string $category = 'all', int $limit = 6, int $offset = 0)
    {
        $builder = $this->where('status', 'published');

        // Apply Filter
        if ($category !== 'all') {
            $builder->where('category', $category);
        }

        return $builder->orderBy('published_at', 'DESC')
                       ->findAll($limit, $offset);
    }

    /**
     * Get published stories, ordered by most recent.
     */
    public function getLatestStories(int $limit = 4)
    {
        return $this->where('status', 'published')
                    ->orderBy('published_at', 'DESC')
                    ->findAll($limit);
    }
    /**
     * Scope to get only published articles
     */

    public function getPublished(int $limit = 3)
    {
        return $this->where('status', 'published')
                    ->orderBy('published_at', 'DESC')
                    ->findAll($limit);
    }
}