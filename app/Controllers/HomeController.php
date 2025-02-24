<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;

class HomeController
{

    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(Request $request)
    {

        $data = [
            'title' => 'Home Page',
            'description' => 'Welcome to the home page'
        ];

        return $this->view->render('pages.index', $data);
    }
}