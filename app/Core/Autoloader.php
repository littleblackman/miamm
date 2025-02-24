<?php

namespace App\Core;

class Autoloader {

    public static function Register() {
        spl_autoload_register(/**
         * @throws \Exception
         */ function($class) {
            $class = str_replace('App\\', '', $class);
            $class = str_replace('\\', '/', $class);
            $file = __DIR__ . '/../' . $class . '.php';
            if (file_exists($file)) {
                require_once $file;
            } else {
                throw new \Exception("Impossible de charger la classe : " . $class);
            }
        });
    }
}