<?php


namespace App\Core;


/**
 * Class View
 * @package App\View
 */
class View
{

    private string $layout = 'layouts/default';

    public function render(string $view, array $data = []): void
    {
        // chemin des vues

        $view = str_replace('.', '/', $view);
        $viewPath = Config::get('APP_ROOT', realpath(__DIR__ . '/../')).'/app/Views/'.$view.'.php';
        $layoutPath = Config::get('APP_ROOT', realpath(__DIR__ . '/../')).'/app/Views/'.$this->layout.'.php';

        // extract data
        extract($data);

        ob_start();
        include($viewPath);
        $content = ob_get_clean();

        if (file_exists($layoutPath)) {
            ob_start();
            include $layoutPath;
            echo ob_get_clean();
        } else {
            echo $content;
        }
    }


}