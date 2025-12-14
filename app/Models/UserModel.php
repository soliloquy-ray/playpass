<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // We can switch to Entities later if needed
    protected $useSoftDeletes   = false; // Set to false since deleted_at column doesn't exist in migration
    protected $allowedFields    = [
        'uuid', 'email', 'phone', 'password_hash', 'google_id', 'facebook_id', 
        'avatar_url', 'role', 'status', 'email_verified_at', 'phone_verified_at', 
        'last_login_at', 'last_activity_at', 'first_name', 'last_name'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation Rules
    protected $validationRules = [
        'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password_hash' => 'permit_empty', // Optional for social logins
        'role'          => 'in_list[customer,admin,super_admin]',
    ];

    // Callbacks
    protected $beforeInsert = ['generateUUID', 'hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hashes the password before saving to the database.
     * Expects 'password' to be passed in the data array, assigns it to 'password_hash'.
     */
    protected function hashPassword(array $data)
    {
        if (! isset($data['data']['password'])) {
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
        $data['data']['uuid'] = Uuid::uuid4()->toString();
        return $data;
    }

    /**
     * Find a user by their Google ID.
     */
    public function findByGoogleId(string $googleId): ?array
    {
        return $this->where('google_id', $googleId)->first();
    }

    /**
     * Find a user by their Facebook ID.
     */
    public function findByFacebookId(string $facebookId): ?array
    {
        return $this->where('facebook_id', $facebookId)->first();
    }
}