<?php

if (!function_exists('dd')) {

    /**
     * retourne les variables et arrête le script
     * @param $vars
     * @return void
     */
    function dd($vars): void
    {
        echo '<pre>';
        print_r($vars);
        echo '</pre>';
        die();
    }
}



if (!function_exists('getLink')) {

    /**
     * Retourne le lien d'une route
     *
     * @param string $name
     * @param array $values
     * @return string
     */
    function getLink(string $name, array $values = []): string
    {

        $route = ($_SESSION['namedRoutes'][$name]) ?? $name;

        if (!empty($values)) {
            preg_match_all('/\{(\w+)\}/', $route, $matches);
            foreach ($matches[1] as $index => $param) {
                if (isset($values[$index])) {
                    $route = str_replace("{" . $param . "}", $values[$index], $route);
                }
            }
        }

        return '/' . ltrim($route, '/');
    }
}
