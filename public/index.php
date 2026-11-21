<?php
/**
 * DaNang PC - Front Controller (Public)
 */

require_once dirname(__DIR__) . '/app/bootstrap.php';

/** @var \App\Router $router */
$router = require dirname(__DIR__) . '/routes/web.php';

$router->dispatch();
