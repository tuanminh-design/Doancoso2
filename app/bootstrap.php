<?php
/**
 * Application Bootstrap & Autoloader
 * DaNang PC E-Commerce
 */

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set default timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Register PSR-4 Autoloader for App\*
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize session
\App\Helpers\Session::start();
