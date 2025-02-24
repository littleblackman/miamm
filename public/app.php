<?php
require_once __DIR__ . '/../app/Core/Autoloader.php';
App\Core\Autoloader::Register();

Use App\Controllers\HomeController;


$controller = new HomeController();
$controller->index();