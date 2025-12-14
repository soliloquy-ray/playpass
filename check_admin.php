<?php
/**
 * Debug script to check admin user in database
 * Run: php check_admin.php
 */

// Simple database connection check
$config = [
    'hostname' => '127.0.0.1',
    'username' => 'root',
    'password' => '',
    'database' => 'playpass_db',
];

$conn = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Checking admin user in database...\n\n";

// Check if admin exists
$result = $conn->query("SELECT id, email, first_name, last_name, role, status, 
                        LENGTH(password_hash) as pwd_length, 
                        LEFT(password_hash, 20) as pwd_preview
                        FROM admins WHERE email = 'admin@playpass.ph'");

if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "✓ Admin user found:\n";
    echo "  ID: {$admin['id']}\n";
    echo "  Email: {$admin['email']}\n";
    echo "  Name: {$admin['first_name']} {$admin['last_name']}\n";
    echo "  Role: {$admin['role']}\n";
    echo "  Status: {$admin['status']}\n";
    echo "  Password Hash Length: {$admin['pwd_length']}\n";
    echo "  Password Hash Preview: {$admin['pwd_preview']}...\n\n";
    
    // Test password verification
    $testPassword = 'admin123';
    $result2 = $conn->query("SELECT password_hash FROM admins WHERE email = 'admin@playpass.ph'");
    if ($result2 && $result2->num_rows > 0) {
        $row = $result2->fetch_assoc();
        $hash = $row['password_hash'];
        if (password_verify($testPassword, $hash)) {
            echo "✓ Password verification: SUCCESS\n";
            echo "  The password 'admin123' matches the hash in database.\n";
        } else {
            echo "✗ Password verification: FAILED\n";
            echo "  The password 'admin123' does NOT match the hash.\n";
            echo "  You may need to reset the password.\n\n";
            echo "To fix, run this SQL:\n";
            echo "UPDATE admins SET password_hash = '" . password_hash('admin123', PASSWORD_DEFAULT) . "' WHERE email = 'admin@playpass.ph';\n";
        }
    }
} else {
    echo "✗ Admin user NOT found in database!\n";
    echo "  Email: admin@playpass.ph\n\n";
    echo "Run the seeder again:\n";
    echo "  php spark db:seed AdminSeeder\n";
}

$conn->close();

