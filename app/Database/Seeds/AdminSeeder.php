<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AdminModel;

class AdminSeeder extends Seeder
{
    /**
     * Creates a default admin account for the PlayPass CMS
     * 
     * Usage:
     * php spark db:seed AdminSeeder
     */
    public function run()
    {
        $adminModel = new AdminModel();
        
        // Check if admin already exists
        $existingAdmin = $adminModel->where('email', 'admin@playpass.ph')->first();
        
        if ($existingAdmin) {
            echo "Admin user already exists. Updating to ensure admin role...\n";
            
            // Ensure the user has admin role and is active
            $adminModel->update($existingAdmin['id'], [
                'role' => 'admin',
                'status' => 'active',
                'first_name' => 'Super',
                'last_name' => 'Admin',
            ]);
            
            echo "✓ Admin user updated successfully!\n";
            echo "  Email: admin@playpass.ph\n";
            echo "  Password: admin123 (change this after first login!)\n";
            return;
        }
        
        // Create new admin user using direct DB insertion to ensure password is hashed
        // Generate UUID
        $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
        
        $adminData = [
            'uuid'           => $uuid,
            'email'          => 'admin@playpass.ph',
            'password_hash'  => password_hash('admin123', PASSWORD_DEFAULT),
            'first_name'     => 'Super',
            'last_name'      => 'Admin',
            'role'           => 'admin',
            'status'         => 'active',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];
        
        // Use direct DB insertion to bypass model restrictions
        $this->db->table('admins')->insert($adminData);
        $adminId = $this->db->insertID();
        
        echo "✓ Admin user created successfully!\n";
        echo "  ID: {$adminId}\n";
        echo "  Email: admin@playpass.ph\n";
        echo "  Password: admin123\n";
        echo "  Role: admin\n";
        echo "\n";
        echo "⚠️  IMPORTANT: Change the password after your first login!\n";
        echo "\n";
        echo "You can now login at: /admin/login\n";
        echo "Then access admin panel at: /admin/dashboard\n";
    }
}

