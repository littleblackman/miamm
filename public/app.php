<?php

require_once __DIR__ . '/../app/bootstrap.php';

Use App\Controllers\HomeController;


$controller = new HomeController();
$controller->index();