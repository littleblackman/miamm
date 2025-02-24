<?php


if (!function_exists('dd')) {
    function dd($vars): void
    {
        echo '<pre>';
        print_r($vars);
        echo '</pre>';
        die();
    }
}
