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

    public function create(Request $request): null
    {
        return $this->view->render('pages.recette.create');
    }

    public function store(Request $request): null
    {
        $recette_url = $request->param('recette_url');
        $recette = $this->recetteService->createFromUrl($recette_url);

        if(!$recette) {

            // creation d'un message d'erreur en session

            return $this->view->redirect('recette_create');
        }

        return $this->view->redirect('recette_edit', ['id' => $recette->getId()]);
    }

    public function edit(Request $request) {
        $id = $request->param('id');
        $recette = $this->recetteService->find($id);
        return $this->view->render('pages.recette.edit', ['recette' => $recette]);
    }

    public function update(Request $request) {

        $recette = $this->recetteService->update($request->params());
        return $this->view->redirect('recette_edit', ['id' => $recette->getId()]);
    }

    public function show(Request $request)
    {
        $recette = $this->recetteService->find($request->param('id'));
        return $this->view->render('pages.recette.show', ['recette' => $recette]);
    }

    public function delete(Request $request) {
        $recette = $this->recetteService->delete($request->param('id'));
        return $this->view->redirect('recette_list');
    }
}