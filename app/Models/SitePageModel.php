<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model for managing CMS-editable site pages
 * like Terms & Conditions, Privacy Policy, and FAQ.
 */
class SitePageModel extends Model
{
    protected $table            = 'site_pages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'slug',
        'title',
        'content',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'slug'  => 'required|max_length[100]',
        'title' => 'required|max_length[255]',
    ];

    protected $validationMessages = [
        'slug' => [
            'required'   => 'Page slug is required.',
            'max_length' => 'Page slug cannot exceed 100 characters.',
        ],
        'title' => [
            'required'   => 'Page title is required.',
            'max_length' => 'Page title cannot exceed 255 characters.',
        ],
    ];

    /**
     * Get a page by its slug
     *
     * @param string $slug The page slug (e.g., 'terms', 'privacy', 'faq')
     * @return array|null
     */
    public function getBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Get all active pages
     *
     * @return array
     */
    public function getActivePages(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('title', 'ASC')
                    ->findAll();
    }

    /**
     * Get all pages (including inactive) for admin
     *
     * @return array
     */
    public function getAllPages(): array
    {
        return $this->orderBy('title', 'ASC')
                    ->findAll();
    }
}
