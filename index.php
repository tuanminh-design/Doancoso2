<?php
/**
 * DaNang PC - Root Entry Point
 * Allows seamless execution when placed directly in XAMPP htdocs without configuring virtual hosts
 */

require_once __DIR__ . '/app/bootstrap.php';

/** @var \App\Router $router */
$router = require __DIR__ . '/routes/web.php';

$router->dispatch();
