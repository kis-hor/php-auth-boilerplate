<?php
$currentYear = date('Y');
$appVersion = '1.0.0';
?>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; <?= htmlspecialchars($currentYear) ?> PHP Auth Boilerplate. All rights reserved.</p>
        <p class="footer-version">v<?= htmlspecialchars($appVersion) ?></p>
    </div>
</footer>

<style>
    .dashboard-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .content {
        flex: 1;
    }

    .footer {
        background-color: #f3f4f6;
        border-top: 1px solid #e5e7eb;
        padding: 2rem;
        margin-top: auto;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .footer p {
        margin: 0;
    }

    .footer-version {
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            gap: 0.5rem;
            text-align: center;
        }
    }
</style>