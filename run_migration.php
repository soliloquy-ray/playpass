<?php
/**
 * Simple migration runner for admins table
 * Run: php run_migration.php
 */

require __DIR__ . '/vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = APPPATH . 'Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
require realpath($bootstrap) ?: $bootstrap;

$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

// Get database connection
$db = \Config\Database::connect();

// Create admins table
$forge = \Config\Database::forge();

$fields = [
    'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
    'uuid' => ['type' => 'VARCHAR', 'constraint' => 36, 'null' => true],
    'email' => ['type' => 'VARCHAR', 'constraint' => 255],
    'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
    'first_name' => ['type' => 'VARCHAR', 'constraint' => 100],
    'last_name' => ['type' => 'VARCHAR', 'constraint' => 100],
    'role' => ['type' => 'ENUM', 'constraint' => ['admin', 'super_admin'], 'default' => 'admin'],
    'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'suspended'], 'default' => 'active'],
    'avatar_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
    'last_login_at' => ['type' => 'DATETIME', 'null' => true],
    'last_activity_at' => ['type' => 'DATETIME', 'null' => true],
    'created_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP'],
    'updated_at' => ['type' => 'DATETIME', 'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'],
];

$forge->addField($fields);
$forge->addKey('id', true);
$forge->addUniqueKey('email');
$forge->addUniqueKey('uuid');

try {
    $forge->createTable('admins');
    echo "✓ Admins table created successfully!\n";
    echo "Now run: php spark db:seed AdminSeeder\n";
} catch (\Exception $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "✓ Admins table already exists.\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

