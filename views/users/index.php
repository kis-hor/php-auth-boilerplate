<?php
// This view is rendered inside the main.php layout
?>
<style>
    .table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #f3f4f6;
    }

    th,
    td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    th {
        font-weight: 600;
        color: #1f2937;
    }

    tbody tr:hover {
        background-color: #f9fafb;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-active {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .role-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .role-admin {
        background-color: #fcd34d;
        color: #78350f;
    }

    .role-manager {
        background-color: #bfdbfe;
        color: #1e3a8a;
    }

    .role-user {
        background-color: #dbeafe;
        color: #0c4a6e;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-small {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        text-decoration: none;
        border-radius: 4px;
        display: inline-block;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background-color: #3b82f6;
        color: white;
    }

    .btn-edit:hover {
        background-color: #2563eb;
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }

    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .alert-error {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .header-section h1 {
        margin: 0;
    }
</style>

<div class="header-section">
    <h1>Manage Users</h1>
    <a href="/users/create" class="btn">+ Add User</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_GET['success']) ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>

<?php if (!empty($users)): ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="role-badge role-<?= htmlspecialchars($user['role']) ?>">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-<?= htmlspecialchars($user['status']) ?>">
                                <?= htmlspecialchars($user['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?= $user['last_login_at'] ? date('M d, Y H:i', strtotime($user['last_login_at'])) : 'Never' ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="/users/edit/<?= $user['id'] ?>" class="btn-small btn-edit">Edit</a>
                                <a href="/users/delete/<?= $user['id'] ?>" class="btn-small btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
        <p style="color: #6b7280; font-size: 1.1rem;">No users found</p>
        <a href="/users/create" class="btn" style="margin-top: 1rem;">Create First User</a>
    </div>
<?php endif; ?>