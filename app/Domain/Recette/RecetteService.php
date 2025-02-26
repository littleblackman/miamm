<?php

namespace App\Domain\Recette;

use AllowDynamicProperties;
use App\Domain\Recette\RecetteRepository;
use App\Domain\Recette\Recette;
use App\Services\ScraperService;

#[AllowDynamicProperties]
class RecetteService {
    private RecetteRepository $repository;

    public function __construct()
    {
        $this->repository = new RecetteRepository();
        $this->scraperService = new ScraperService();
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

    public function createFromUrl(string $url): void
    {
        ['html' => $html, 'data' => $data] = $this->scraperService->getContent($url);
        $is_valid = $this->isValidData($data);
        if($is_valid) {
            $recette = $this->createRecetteFromData($data);
        } else {
            $recette = $this->createRecetteFromIA($html, $data);
        }
        $this->repository->save($recette);
        return $recette;
    }

    public function createRecetteFromData(array $data): Recette
    {
        $recette = new Recette();
        $recette->hydrate($data);
        return $recette;
    }

    public function isValidData($array) {
        // check if data is full
        $is_valid = true;
        foreach ($array as $key => $value) {
                if(!is_array($value)) {
                    if(empty($value))  $is_valid = false;
                } else {
                    if(count($value) > 0) $is_valid = false;
                }
        }
        return $is_valid;
    }

    public function createRecetteFromIA(string $html, array $data): Recette
    {
        // IA to create recette
        $recette = new Recette();
        $recette->hydrate($data);
        return $recette;
    }
}
