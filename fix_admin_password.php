<?php
/**
 * Fix admin password hash
 * Run: php fix_admin_password.php
 */

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

$password = 'admin123';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE admins SET password_hash = ? WHERE email = 'admin@playpass.ph'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $passwordHash);

if ($stmt->execute()) {
    echo "✓ Admin password updated successfully!\n";
    echo "  Email: admin@playpass.ph\n";
    echo "  Password: admin123\n";
    echo "\n";
    echo "You can now login at: /admin/login\n";
} else {
    echo "✗ Error updating password: " . $stmt->error . "\n";
}

$stmt->close();
$conn->close();

