<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Domain\Recette\RecetteService;

class HomeController
{

    private View $view;
    private RecetteService $recetteService;

    public function __construct()
    {
        $this->view = new View();
        $this->recetteService = new RecetteService();
    }

    public function index(Request $request)
    {
        $latest = $this->recetteService->getLatest(3);
        return $this->view->render('pages.index', ['latest' => $latest]);
    }
}