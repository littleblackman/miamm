<?php
require_once __DIR__ . '/../app/bootstrap.php';
Use App\Core\Router;

$router = new Router();
$router->dispatch();

