<?php
require_once __DIR__ . '/Core/Autoloader.php';
require_once __DIR__ . '/Core/Config.php';

use App\Core\Autoloader;
use App\Core\Config;
use App\Core\ErrorHandler;
use App\Core\Router;

// load all classes
Config::load(__DIR__ . '/.env');
Autoloader::register();
//ErrorHandler::register();

// Gérer les erreurs en fonction de l'environnement
if (Config::get('APP_ENV') === 'dev' && Config::get('DISPLAY_ERRORS') === 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);
    ini_set('error_log', __DIR__ . '/logs/error.log');
}
