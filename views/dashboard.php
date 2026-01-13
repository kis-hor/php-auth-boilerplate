<?php
// This view is rendered inside the main.php layout
// The $content variable contains this view's HTML
?>

<h1>Welcome to the Inventory Dashboard</h1>
<p>You are logged in as: <strong><?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?></strong></p>

<div class="dashboard-grid">
    <div class="card">
        <h3>Products</h3>
        <p>Manage your products</p>
        <a href="/products" class="btn">View Products</a>
    </div>
    <div class="card">
        <h3>Orders</h3>
        <p>View and manage orders</p>
        <a href="/orders" class="btn">View Orders</a>
    </div>
    <div class="card">
        <h3>Inventory</h3>
        <p>Track stock levels</p>
        <a href="/inventory" class="btn">View Inventory</a>
    </div>

    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <div class="card">
            <h3>Manage Users</h3>
            <p>Create, edit, and manage user accounts</p>
            <a href="/users" class="btn">Manage Users</a>
        </div>
    <?php endif; ?>
</div>