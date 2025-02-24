<?php

namespace App\Controllers;

use AllowDynamicProperties;
use App\Core\Request;
use App\Core\View;
use App\Domain\Recette\RecetteService;

#[AllowDynamicProperties]
class RecetteController
{

    private View $view;
    private RecetteService $recetteService;

    public function __construct()
    {
        $this->view = new View();
        $this->recetteService = new RecetteService();
    }

    public function list(Request $request): null
    {
        $recettes = $this->recetteService->getAllRecettes();
        return $this->view->render('pages.recette.index', ['recettes' => $recettes]);
    }
}