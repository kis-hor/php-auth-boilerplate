<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 2rem;
            color: #1f2937;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #1f2937;
        }

        input,
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .alert li {
            margin-bottom: 0.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .btn-secondary {
            background-color: #e5e7eb;
            color: #1f2937;
        }

        .btn-secondary:hover {
            background-color: #d1d5db;
        }

        .navbar {
            background-color: #4f46e5;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-brand {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .nav-links a:hover {
            opacity: 0.8;
        }

        .content {
            padding: 2rem;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="nav-brand">Inventory System</div>
            <ul class="nav-links">
                <li><a href="/">Dashboard</a></li>
                <li><a href="/users">Users</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </nav>

        <main class="content">
            <div class="form-container">
                <h1>Add New User</h1>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/users/store">
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimum 6 characters"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select id="role" name="role" required>
                            <option value="user" <?= ($old['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>User</option>
                            <option value="manager" <?= ($old['role'] ?? '') === 'manager' ? 'selected' : '' ?>>Manager</option>
                            <option value="admin" <?= ($old['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="active" <?= ($old['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create User</button>
                        <a href="/users" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>