<?php

/**
 * Database Seeder Script
 * Run from terminal: php seed.php
 */

// Load classes
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Models/User.php';

use App\Core\Database;
use App\Models\User;

class DatabaseSeeder
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function seed(): void
    {
        echo "🌱 Starting database seeding...\n";

        // Clear existing data (optional)
        // $this->clearUsers();

        $this->seedUsers();

        echo "✅ Seeding completed successfully!\n";
    }

    private function seedUsers(): void
    {
        echo "\n📝 Seeding users...\n";

        $users = [
            [
                'username' => 'manager',
                'email' => 'manager@example.com',
                'password' => 'password',
                'role' => 'manager',
                'status' => 'active'
            ],
            [
                'username' => 'staff',
                'email' => 'staff@example.com',
                'password' => 'password',
                'role' => 'staff',
                'status' => 'active'
            ]
        ];

        $userModel = new User();

        foreach ($users as $user) {
            $username = $user['username'];

            // Check if user already exists
            if (\App\Models\User::usernameExists($username)) {
                echo "  ⏭️  Skipping '{$username}' (already exists)\n";
                continue;
            }

            $passwordHash = password_hash($user['password'], PASSWORD_BCRYPT);

            try {
                $userId = $userModel->create([
                    'username' => $username,
                    'email' => $user['email'],
                    'password_hash' => $passwordHash,
                    'role' => $user['role'],
                    'status' => $user['status'],
                    'created_by' => null
                ]);

                echo "  ✓ Created user: {$username} (ID: {$userId}) - Role: {$user['role']}\n";
            } catch (\Exception $e) {
                echo "  ✗ Error creating '{$username}': " . $e->getMessage() . "\n";
            }
        }

        echo "\n📊 User seeding complete\n";
    }

    private function clearUsers(): void
    {
        echo "🧹 Clearing existing users...\n";

        try {
            $this->db->exec("DELETE FROM users");
            echo "  ✓ Users table cleared\n";
        } catch (\Exception $e) {
            echo "  ✗ Error clearing users: " . $e->getMessage() . "\n";
        }
    }
}

// Run seeder
$seeder = new DatabaseSeeder();
$seeder->seed();

echo "\n" . str_repeat("=", 50) . "\n";
echo "TEST PLAN:\n";
echo str_repeat("=", 50) . "\n";
echo "1. Login as admin:\n";
echo "   - Username: admin\n";
echo "   - Password: password\n";
echo "   - Expected: Access to all features including user management\n\n";

echo "2. Login as manager:\n";
echo "   - Username: manager\n";
echo "   - Password: password\n";
echo "   - Expected: Can view/access, restrictions apply\n\n";

echo "3. Login as staff:\n";
echo "   - Username: staff\n";
echo "   - Password: password\n";
echo "   - Expected: Limited access\n\n";

echo "4. Test user management at: http://localhost:8000/users\n";
echo "5. Test creating, editing, deleting users\n";
echo "6. Verify role-based access control\n";
echo str_repeat("=", 50) . "\n";
