<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display all users
     */
    public function index()
    {
        $userModel = new User();
        $users = $userModel->getAllUsers();

        $this->view('users/index', [
            'users' => $users
        ]);
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $this->view('users/create');
    }

    /**
     * Store new user in database
     */
    public function store()
    {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $status = $_POST['status'] ?? 'active';

        $errors = [];

        // Validate role - only allow specific values
        $allowedRoles = ['admin', 'manager', 'staff', 'user'];
        if (!in_array($role, $allowedRoles)) {
            $errors[] = 'Invalid role selected';
            $role = 'user';  // Reset to default
        }

        // Validate status
        $allowedStatuses = ['active', 'inactive'];
        if (!in_array($status, $allowedStatuses)) {
            $errors[] = 'Invalid status selected';
            $status = 'active';  // Reset to default
        }

        // Validation
        if (empty($username)) {
            $errors[] = 'Username is required';
        } elseif (User::usernameExists($username)) {
            $errors[] = 'Username already exists';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        } elseif (User::emailExists($email)) {
            $errors[] = 'Email already exists';
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if (!empty($errors)) {
            $this->view('users/create', [
                'errors' => $errors,
                'old' => compact('username', 'email', 'role', 'status')
            ]);
            return;
        }

        // Create user
        $userModel = new User();
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $userModel->create([
                'username' => $username,
                'email' => $email,
                'password_hash' => $passwordHash,
                'role' => $role,
                'status' => $status,
                'created_by' => Auth::user()['id']
            ]);

            $this->redirect('/users?success=User created successfully');
        } catch (\Exception $e) {
            $this->view('users/create', [
                'errors' => ['Error creating user: ' . $e->getMessage()],
                'old' => compact('username', 'email', 'role', 'status')
            ]);
        }
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $userModel = new User();
        $user = $userModel->findById((int)$id);

        if (!$user) {
            http_response_code(404);
            echo "404 | User not found";
            return;
        }

        $this->view('users/edit', [
            'user' => $user
        ]);
    }

    /**
     * Update user in database
     */
    public function update($id)
    {
        $userModel = new User();
        $user = $userModel->findById((int)$id);

        if (!$user) {
            http_response_code(404);
            echo "404 | User not found";
            return;
        }

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? $user['role'];
        $status = $_POST['status'] ?? $user['status'];

        $errors = [];

        // Validate role - only allow specific values
        $allowedRoles = ['admin', 'manager', 'staff', 'user'];
        if (!in_array($role, $allowedRoles)) {
            $errors[] = 'Invalid role selected';
            $role = $user['role'];  // Reset to original
        }

        // Validate status
        $allowedStatuses = ['active', 'inactive'];
        if (!in_array($status, $allowedStatuses)) {
            $errors[] = 'Invalid status selected';
            $status = $user['status'];  // Reset to original
        }

        // Validation
        if (empty($username)) {
            $errors[] = 'Username is required';
        } elseif (User::usernameExists($username, (int)$id)) {
            $errors[] = 'Username already exists';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        } elseif (User::emailExists($email, (int)$id)) {
            $errors[] = 'Email already exists';
        }

        if (!empty($password) && strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if (!empty($errors)) {
            $this->view('users/edit', [
                'user' => $user,
                'errors' => $errors
            ]);
            return;
        }

        // Update user
        $updateData = [
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'status' => $status
        ];

        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        try {
            $userModel->update((int)$id, $updateData);
            $this->redirect('/users?success=User updated successfully');
        } catch (\Exception $e) {
            $this->view('users/edit', [
                'user' => $user,
                'errors' => ['Error updating user: ' . $e->getMessage()]
            ]);
        }
    }

    /**
     * Soft delete user
     */
    public function delete($id)
    {
        $userModel = new User();
        $user = $userModel->findById((int)$id);

        if (!$user) {
            http_response_code(404);
            echo "404 | User not found";
            return;
        }

        // Prevent deleting self
        if ($user['id'] === Auth::user()['id']) {
            $this->redirect('/users?error=Cannot delete your own account');
            return;
        }

        try {
            $userModel->delete((int)$id);
            $this->redirect('/users?success=User deleted successfully');
        } catch (\Exception $e) {
            $this->redirect('/users?error=Error deleting user: ' . $e->getMessage());
        }
    }
}
