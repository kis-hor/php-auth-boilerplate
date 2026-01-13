<?php

namespace App\Core;

class Debug
{
    /**
     * Display all superglobals and request info
     */
    public static function showAll(): void
    {
        echo "<pre style='background: #f5f5f5; padding: 15px; border: 1px solid #ddd; border-radius: 4px;'>";
        echo "<h3>🔍 DEBUG INFO</h3>";

        self::showServer();
        self::showPost();
        self::showGet();
        self::showSession();
        self::showCookie();

        echo "</pre>";
    }

    /**
     * Show $_SERVER superglobal
     */
    public static function showServer(): void
    {
        echo "<h4>📋 \$_SERVER</h4>";
        echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "<br>";
        echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "<br>";
        echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "<br>";
        echo "REMOTE_ADDR: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "<br>";
        echo "SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "<br>";
        echo "PHP_VERSION: " . phpversion() . "<br><br>";
    }

    /**
     * Show $_POST superglobal
     */
    public static function showPost(): void
    {
        echo "<h4>📤 \$_POST</h4>";
        if (empty($_POST)) {
            echo "<span style='color: #999;'>Empty</span><br><br>";
            return;
        }
        foreach ($_POST as $key => $value) {
            $displayValue = is_array($value) ? json_encode($value) : htmlspecialchars($value);
            echo "<strong>{$key}:</strong> {$displayValue}<br>";
        }
        echo "<br>";
    }

    /**
     * Show $_GET superglobal
     */
    public static function showGet(): void
    {
        echo "<h4>📥 \$_GET</h4>";
        if (empty($_GET)) {
            echo "<span style='color: #999;'>Empty</span><br><br>";
            return;
        }
        foreach ($_GET as $key => $value) {
            $displayValue = is_array($value) ? json_encode($value) : htmlspecialchars($value);
            echo "<strong>{$key}:</strong> {$displayValue}<br>";
        }
        echo "<br>";
    }

    /**
     * Show $_SESSION superglobal
     */
    public static function showSession(): void
    {
        echo "<h4>🔐 \$_SESSION</h4>";
        if (empty($_SESSION)) {
            echo "<span style='color: #999;'>Empty</span><br><br>";
            return;
        }
        foreach ($_SESSION as $key => $value) {
            $displayValue = is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : htmlspecialchars((string)$value);
            echo "<strong>{$key}:</strong> <pre style='margin: 5px 0;'>{$displayValue}</pre>";
        }
        echo "<br>";
    }

    /**
     * Show $_COOKIE superglobal
     */
    public static function showCookie(): void
    {
        echo "<h4>🍪 \$_COOKIE</h4>";
        if (empty($_COOKIE)) {
            echo "<span style='color: #999;'>Empty</span><br><br>";
            return;
        }
        foreach ($_COOKIE as $key => $value) {
            echo "<strong>{$key}:</strong> " . htmlspecialchars($value) . "<br>";
        }
        echo "<br>";
    }

    /**
     * Display database query result with nice formatting
     */
    public static function showData($data, string $title = "DATA"): void
    {
        echo "<pre style='background: #fff3cd; padding: 15px; border: 1px solid #ffc107; border-radius: 4px; margin: 15px 0;'>";
        echo "<h4>📊 {$title}</h4>";

        if (is_null($data)) {
            echo "<span style='color: #d32f2f;'>NULL</span>";
        } elseif (is_array($data)) {
            echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            echo htmlspecialchars((string)$data);
        }

        echo "</pre>";
    }

    /**
     * Log error to file and display
     */
    public static function logError(string $message, $data = null): void
    {
        $logFile = __DIR__ . '/../../storage/logs/debug.log';

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}";

        if ($data !== null) {
            $logMessage .= " | " . json_encode($data);
        }

        $logMessage .= "\n";

        // Write to file
        error_log($logMessage, 3, $logFile);

        // Display in browser
        echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #d32f2f; margin: 10px 0;'>";
        echo "<strong style='color: #d32f2f;'>❌ ERROR:</strong> " . htmlspecialchars($message);
        if ($data !== null) {
            echo "<pre style='margin-top: 5px;'>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</pre>";
        }
        echo "</div>";
    }

    /**
     * Log success message
     */
    public static function logSuccess(string $message, $data = null): void
    {
        echo "<div style='background: #e8f5e9; padding: 10px; border-left: 4px solid #4caf50; margin: 10px 0;'>";
        echo "<strong style='color: #4caf50;'>✅ SUCCESS:</strong> " . htmlspecialchars($message);
        if ($data !== null) {
            echo "<pre style='margin-top: 5px;'>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</pre>";
        }
        echo "</div>";
    }

    /**
     * Log info message
     */
    public static function logInfo(string $message, $data = null): void
    {
        echo "<div style='background: #e3f2fd; padding: 10px; border-left: 4px solid #2196f3; margin: 10px 0;'>";
        echo "<strong style='color: #2196f3;'>ℹ️ INFO:</strong> " . htmlspecialchars($message);
        if ($data !== null) {
            echo "<pre style='margin-top: 5px;'>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</pre>";
        }
        echo "</div>";
    }
}
