<?php

use App\Core\Auth;
?>

<nav class="navbar">
    <div class="nav-brand">Inventory System</div>
    <ul class="nav-links">
        <!-- Dashboard Link (always visible if logged in) -->
        <?php if (Auth::check()): ?>
            <li>
                <a href="/" class="<?= isActivePage('/') ? 'active' : '' ?>">
                    Dashboard
                </a>
            </li>

            <!-- Users Link (Admin only) -->
            <?php if (Auth::hasRole(['admin'])): ?>
                <li>
                    <a href="/users" class="<?= isActivePage('/users') ? 'active' : '' ?>">
                        Manage Users
                    </a>
                </li>
            <?php endif; ?>

            <!-- Inventory Link (All authenticated users) -->
            <li>
                <a href="/inventory" class="<?= isActivePage('/inventory') ? 'active' : '' ?>">
                    Inventory
                </a>
            </li>

            <!-- Logout Link -->
            <li>
                <a href="/logout">Logout</a>
            </li>
        <?php else: ?>
            <!-- Not logged in -->
            <li>
                <a href="/login" class="<?= isActivePage('/login') ? 'active' : '' ?>">
                    Login
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<style>
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
        margin: 0;
        padding: 0;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        transition: opacity 0.3s;
        padding: 0.5rem 0;
        border-bottom: 2px solid transparent;
    }

    .nav-links a:hover {
        opacity: 0.8;
    }

    .nav-links a.active {
        border-bottom-color: white;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            gap: 1rem;
        }

        .nav-links {
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }
    }
</style>

<?php
/**
 * Helper function to check if a page is currently active
 */
function isActivePage(string $route): bool
{
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Normalize both URIs
    $currentUri = rtrim($currentUri, '/') ?: '/';
    $route = rtrim($route, '/') ?: '/';

    // Exact match or starts with (for routes with sub-paths)
    return $currentUri === $route || strpos($currentUri, $route . '/') === 0;
}
?>