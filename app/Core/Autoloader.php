<?php

namespace App\Core;

class Autoloader {


    /**
     * @var array|string[]
     */
    private static array $directories = [
        'app/Controllers/',
        'app/Core/',
        'app/Models/',
        'app/Views/',
    ];

    /**
     * @return void
     * @throws \Exception
     */
    public static function Register(): void
    {

        spl_autoload_register(function ($class) {

            // Récupérer le chemin racine défini dans .env
            $baseDir = Config::get('APP_ROOT', realpath(__DIR__ . '/../'));

            $file = $baseDir.'/'.str_replace('App', 'app', $class).'.php';
            $file = str_replace('\\', '/', $file);

            if (file_exists($file)) {
                    require_once $file;
                    return;
                } else {
                throw new \Exception("Impossible de charger la classe : " . $class . " (Tenté : $file)");
            }

        });
    }
}