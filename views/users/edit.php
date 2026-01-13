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

    .password-note {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
</style>

<div class="form-container">
    <h1>Edit User</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/users/update/<?= $user['id'] ?>">
        <div class="form-group">
            <label for="username">Username *</label>
            <input
                type="text"
                id="username"
                name="username"
                value="<?= htmlspecialchars($user['username']) ?>"
                required>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($user['email']) ?>"
                required>
        </div>

        <div class="form-group">
            <label for="password">Password (Leave blank to keep current)</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimum 6 characters">
            <div class="password-note">Leave empty to keep the current password</div>
        </div>

        <div class="form-group">
            <label for="role">Role *</label>
            <select id="role" name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status *</label>
            <select id="status" name="status" required>
                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="/users" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>