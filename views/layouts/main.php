<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Inventory System') ?></title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <?php if (!empty($additionalCSS)): ?>
        <?= $additionalCSS ?>
    <?php endif; ?>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header/Navigation -->
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <!-- Main Content -->
        <main class="content">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error" style="margin-bottom: 2rem;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success" style="margin-bottom: 2rem;">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Child view content injected here -->
            <?= $content ?? '' ?>
        </main>

        <!-- Footer -->
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </div>

    <?php if (!empty($additionalJS)): ?>
        <?= $additionalJS ?>
    <?php endif; ?>
</body>

</html>