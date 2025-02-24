<?php

namespace App\Core;

class ErrorHandler
{

    /**
     * @return void
     */
    public static function register(): void
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /**
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return void
     * @throws \ErrorException
     */
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * @param \Throwable $exception
     * @return void
     */
    // Gestion des exceptions
    public static function handleException(\Throwable $exception): void
    {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] " . get_class($exception) . ": " .
            $exception->getMessage() . " in " . $exception->getFile() .
            " on line " . $exception->getLine() . "\n";

        error_log($logMessage, 3, __DIR__ . '/../logs/error.log');

        if (Config::get('APP_ENV') === 'dev') {
            http_response_code(500);
            echo "<h1>Erreur interne</h1>";
            echo "<p><strong>Message:</strong> " . $exception->getMessage() . "</p>";
            echo "<p><strong>Fichier:</strong> " . $exception->getFile() . "</p>";
            echo "<p><strong>Ligne:</strong> " . $exception->getLine() . "</p>";
            echo "<pre>" . $exception->getTraceAsString() . "</pre>";
        } else {
            http_response_code(500);
            include __DIR__ . '/../Views/errors/500.php';
        }
    }

    /**
     * @return void
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null) {
            self::handleException(new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }
    }

}