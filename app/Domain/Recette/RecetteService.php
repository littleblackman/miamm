<?php

namespace App\Domain\Recette;

use App\Domain\Recette\RecetteRepository;
use App\Domain\Recette\Recette;

class RecetteService {
    private RecetteRepository $repository;

    public function __construct()
    {
        $this->repository = new RecetteRepository();
    }

    public function getAllRecettes(): array
    {
        return $this->repository->findAll();
    }

    public function createRecette(string $titre, string $description, string $categorie): bool
    {
        $recette = new Recette($titre, $description, $categorie);
        return $this->repository->save($recette);
    }
}
