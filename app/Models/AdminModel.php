<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class AdminModel extends Model
{
    protected $table            = 'admins';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'uuid', 'email', 'password_hash', 'first_name', 'last_name',
        'role', 'status', 'avatar_url', 'last_login_at', 'last_activity_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'email'         => 'required|valid_email|is_unique[admins.email,id,{id}]',
        'password_hash' => 'permit_empty',
        'role'          => 'in_list[admin,super_admin]',
        'status'        => 'in_list[active,inactive,suspended]',
    ];

    protected $beforeInsert = ['generateUUID', 'hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hashes the password before saving to the database.
     */
    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        unset($data['data']['password']);

        return $data;
    }

    /**
     * Generates a UUID for new records.
     */
    protected function generateUUID(array $data)
    {
        if (!isset($data['data']['uuid']) || empty($data['data']['uuid'])) {
            $data['data']['uuid'] = Uuid::uuid4()->toString();
        }
        return $data;
    }

    /**
     * Get active admins only
     */
    public function getActiveAdmins()
    {
        return $this->where('status', 'active')->findAll();
    }
}

