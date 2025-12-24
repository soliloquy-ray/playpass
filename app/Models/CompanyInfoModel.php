<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model for managing company information
 * like address, phone number, email, and copyright text.
 */
class CompanyInfoModel extends Model
{
    protected $table            = 'company_info';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'key',
        'value',
        'label',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';  // No created_at field
    protected $updatedField  = 'updated_at';

    /**
     * Get a single company info value by key
     *
     * @param string $key The info key (e.g., 'address', 'phone', 'email')
     * @return string|null
     */
    public function get(string $key): ?string
    {
        $result = $this->where('key', $key)->first();
        return $result ? $result['value'] : null;
    }

    /**
     * Get all company info as a key-value array
     *
     * @return array
     */
    public function getAll(): array
    {
        $results = $this->findAll();
        $info = [];
        
        foreach ($results as $row) {
            $info[$row['key']] = $row['value'];
        }
        
        return $info;
    }

    /**
     * Get all company info with labels for admin
     *
     * @return array
     */
    public function getAllWithLabels(): array
    {
        return $this->orderBy('id', 'ASC')->findAll();
    }

    /**
     * Update a company info value by key
     *
     * @param string $key The info key
     * @param string $value The new value
     * @return bool
     */
    public function updateByKey(string $key, string $value): bool
    {
        $existing = $this->where('key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], ['value' => $value]);
        }
        
        return false;
    }

    /**
     * Upsert company info (insert or update)
     *
     * @param string $key
     * @param string $value
     * @param string $label
     * @return bool
     */
    public function upsert(string $key, string $value, string $label = ''): bool
    {
        $existing = $this->where('key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], ['value' => $value]);
        }
        
        return (bool) $this->insert([
            'key'   => $key,
            'value' => $value,
            'label' => $label ?: ucfirst(str_replace('_', ' ', $key)),
        ]);
    }
}
