<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        text-align: center;
        background: white;
        padding: 3rem 2rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 2rem auto;
    }

    .error-code {
        font-size: 8rem;
        font-weight: 900;
        color: #667eea;
        line-height: 1;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .error-title {
        font-size: 2.5rem;
        color: #1f2937;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .error-message {
        font-size: 1.1rem;
        color: #6b7280;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .requested-page {
        background-color: #f3f4f6;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        word-break: break-all;
        color: #1f2937;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn {
        padding: 0.875rem 2rem;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-primary {
        background-color: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background-color: #5568d3;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background-color: #e5e7eb;
        color: #1f2937;
    }

    .btn-secondary:hover {
        background-color: #d1d5db;
        transform: translateY(-2px);
    }

    .illustration {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @media (max-width: 640px) {
        .error-code {
            font-size: 5rem;
        }

        .error-title {
            font-size: 1.8rem;
        }

        .error-message {
            font-size: 1rem;
        }

        .container {
            padding: 2rem 1.5rem;
            margin: 1rem auto;
        }

        .button-group {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="container">
    <div class="illustration">🔍</div>
    <div class="error-code">404</div>
    <h1 class="error-title">Page Not Found</h1>
    <p class="error-message">Sorry, the page you're looking for doesn't exist or has been moved.</p>

    <?php if (!empty($requestedPath)): ?>
        <div class="requested-page">
            Requested: <?= htmlspecialchars($requestedPath) ?>
        </div>
    <?php endif; ?>

    <div class="button-group">
        <a href="/" class="btn btn-primary">← Back to Dashboard</a>
        <a href="/users" class="btn btn-secondary">View Users</a>
    </div>
</div>