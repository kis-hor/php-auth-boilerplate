<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    /**
     * Find user by ID
     */
    public function findById(int $id): ?array
    {
        return $this->find($id);
    }

    /**
     * Find user by username
     */
    public static function findByUsername(string $username): ?array
    {
        $instance = new self();

        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $result = $instance->query($sql, ['username' => $username]);

        if (!$result || empty($result)) {
            return null;
        }

        return $result[0];
    }

    /**
     * Get all users (excluding soft-deleted)
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM users WHERE status != 'inactive' ORDER BY created_at DESC";
        return $this->query($sql);
    }

    /**
     * Create a new user
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO users (username, email, password_hash, role, status, created_by, created_at) 
                VALUES (:username, :email, :password_hash, :role, :status, :created_by, NOW())";

        $this->execute($sql, [
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'role' => $data['role'] ?? 'user',
            'status' => $data['status'] ?? 'active',
            'created_by' => $data['created_by'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update user data
     */
    public function update(int $id, array $data): bool
    {
        $updates = [];
        $params = ['id' => $id];

        if (isset($data['username'])) {
            $updates[] = "username = :username";
            $params['username'] = $data['username'];
        }

        if (isset($data['email'])) {
            $updates[] = "email = :email";
            $params['email'] = $data['email'];
        }

        if (isset($data['password_hash'])) {
            $updates[] = "password_hash = :password_hash";
            $params['password_hash'] = $data['password_hash'];
        }

        if (isset($data['role'])) {
            $updates[] = "role = :role";
            $params['role'] = $data['role'];
        }

        if (isset($data['status'])) {
            $updates[] = "status = :status";
            $params['status'] = $data['status'];
        }

        if (empty($updates)) {
            return true;
        }

        $updates[] = "updated_at = NOW()";
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = :id";

        return $this->execute($sql, $params);
    }

    /**
     * Soft delete user (set status to inactive)
     */
    public function delete(int $id): bool
    {
        return $this->execute(
            "UPDATE users SET status = 'inactive', updated_at = NOW() WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Increment login attempts
     */
    public static function incrementLoginAttempts(int $userId): void
    {
        $instance = new self();
        $instance->execute(
            "UPDATE users SET login_attempts = login_attempts + 1 WHERE id = :id",
            ['id' => $userId]
        );

        // Lock account after 5 failed attempts for 15 minutes
        $user = $instance->find($userId);
        if ($user && $user['login_attempts'] >= 5) {
            $instance->execute(
                "UPDATE users SET locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE id = :id",
                ['id' => $userId]
            );
        }
    }

    /**
     * Reset login attempts
     */
    public static function resetLoginAttempts(int $userId): void
    {
        $instance = new self();
        $instance->execute(
            "UPDATE users SET login_attempts = 0 WHERE id = :id",
            ['id' => $userId]
        );
    }

    /**
     * Update last login timestamp
     */
    public static function updateLastLogin(int $userId): void
    {
        $instance = new self();
        $instance->execute(
            "UPDATE users SET last_login_at = NOW(), login_attempts = 0 WHERE id = :id",
            ['id' => $userId]
        );
    }

    /**
     * Check if username exists
     */
    public static function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $instance = new self();
        $sql = "SELECT id FROM users WHERE username = :username";
        $params = ['username' => $username];

        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $result = $instance->query($sql, $params);
        return !empty($result);
    }

    /**
     * Check if email exists
     */
    public static function emailExists(string $email, ?int $excludeId = null): bool
    {
        $instance = new self();
        $sql = "SELECT id FROM users WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $result = $instance->query($sql, $params);
        return !empty($result);
    }
}
