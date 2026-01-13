<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body>

    <div class="dashboard-container">
        <nav class="navbar">
            <div class="nav-brand">Inventory System</div>
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </nav>

        <main class="content">
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
            </div>
        </main>
    </div>

</body>

</html>