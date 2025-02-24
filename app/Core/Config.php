<?php

namespace App\Core;


class Config {

    private static array $config = [];

    /**
     * @param string $file
     * @return void
     * @throws \Exception
     */
    public static function load (string $file): void
    {
        if(!file_exists($file)) {
            throw new \Exception("Fichier de configuration introuvable");
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$key, $value] = explode('=', $line, 2);
            self::$config[trim($key)] = trim($value);
        }
    }

    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    public static function get(string $key, string $default = ''): string
    {
        return self::$config[$key] ?? $default;
    }

}