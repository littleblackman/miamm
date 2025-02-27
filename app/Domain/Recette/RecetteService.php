<?php

namespace App\Domain\Recette;

use AllowDynamicProperties;
use App\Domain\Recette\RecetteRepository;
use App\Domain\Recette\Recette;
use App\Services\ScraperService;
use App\Services\IAService;
use App\Domain\Ingredient\IngredientRepository;

#[AllowDynamicProperties]
class RecetteService {
    private RecetteRepository $recetteRepository;
    private ScraperService $scraperService;
    private IAService $iaService;
    private IngredientRepository $ingredientRepository;


    public function __construct()
    {
        $this->recetteRepository = new RecetteRepository();
        $this->scraperService = new ScraperService();
        $this->iaService = new IAService();
        $this->ingredientRepository = new IngredientRepository();
    }

    public function getAllRecettes(): array
    {
        return $this->recetteRepository->findAll();
    }

    public function find($id) {
        return $this->recetteRepository->findById($id);
    }

    public function getLatest($nb = 3): array
    {
        return $this->recetteRepository->findLatest($nb);
    }

    public function update($data) {
        return $this->createRecetteFromData($data);
    }

    public function createFromUrl(string $url): ?Recette
    {
        ['html' => $html, 'data' => $data] = $this->scraperService->getContent($url);

        if(!$html || !$data) {
            return null;
        }

        $is_valid = $this->isValidData($data);
        if($is_valid) {
            $recette = $this->createRecetteFromData($data);
        } else {
            $recette = $this->createRecetteFromIA($html, $data);
        }
        return $recette;
    }

    public function createRecetteFromData(array $data): Recette
    {

        $ingredients = $data['ingredients'];
        unset($data['ingredients']);

        // save recette
        $recette = new Recette($data);
        $recette = $this->recetteRepository->save($recette);

        // save jointure ingredients_recette
        foreach($ingredients as $ingredient) {
            $this->recetteRepository->addIngredient($recette->getId(), $ingredient);
        }

        // ajout des ingrédients à recette
        $recette->setIngredients($ingredients);

        return $recette;
    }

    public function delete($id): bool
    {
        return $this->recetteRepository->delete($id);
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

        // transforme data to string
        $dataJson = json_encode($data);


       $prompt = "Tu es un expert en extraction de données structurées. Voici une recette extraite d'un site de cuisine.  
                    Je te fournis :  
                    1. **Le texte brut** de la recette  
                    2. **Le HTML** de la page  
                    3. **Les données déjà extraites** sous forme d'un tableau associatif (JSON incomplet)  
                    
                    Ton objectif est de **corriger et compléter ces données de manière stricte**.  
                    - Remplis les valeurs manquantes (`difficulty`, `cost`, `ingredients` complets avec quantités et unités).  
                    - Corrige les erreurs d’extraction.  
                    - Assure-toi que toutes les informations du HTML sont bien intégrées dans le JSON.  
                    - Ne modifie pas les clés du JSON existant, mais complète et corrige les valeurs.  
                    - Retourne uniquement le JSON final **sans ajout de texte ou de commentaires.
                    ---------
                    voici le résultat du scraping :
                    ".$html." et voici les données scrapées en JSON : ".$dataJson." 
                    
                    NB: dans le json ajoute un champs 'description', 'tags' et un champs 'category' en fonction de ce que tu as compris.
                    Pour la category c'est plutot simple comme entrée, plat, dessert, etc...
                    Pour les tags tu peux mettre des mots clés en rapport avec la recette. Séparé par des vraies virgules.
                    
                    N'oublie pas je veux strictement en retour le tableau associatif JSON complet et corrigé.
                    " ;
       $result = $this->iaService->send($prompt); // uncomment

       // mock result
       /**
        $result = "```json
                    {
                        \"title\": \"Coquilles Saint Jacques sur lit de poireaux\",
                        \"time_total\": \"35 min\",
                        \"time_preparation\": \"15 min\",
                        \"time_repos\": \"-\",
                        \"time_cuisson\": \"20 min\",
                        \"difficulty\": \"facile\",
                        \"cost\": \"assez cher\",
                        \"ingredients\": [
                            { \"name\": \"muscade\", \"quantity\": \"1 pincée\" },
                            { \"name\": \"sel\", \"quantity\": \"1 pincée\" },
                            { \"name\": \"poivre\", \"quantity\": \"1 pincée\" },
                            { \"name\": \"huile\", \"quantity\": \"2\", \"unit\": \"cuillères à soupe\" },
                            { \"name\": \"crème fraîche\", \"quantity\": \"2\", \"unit\": \"cuillères à soupe\" },
                            { \"name\": \"farine\", \"quantity\": \"2\", \"unit\": \"cuillères à soupe\" },
                            { \"name\": \"poireaux\", \"quantity\": \"4\" },
                            { \"name\": \"noix de saint-jacques\", \"quantity\": \"12\" }
                        ],
                        \"steps\": [
                            \"Ne garder que les blancs des poireaux. Les émincer en rondelles les plus fines possibles (1mm).\", 
                            \"Les faire cuire à feu doux dans une poêle avec 1/2 verre d'eau jusqu'à ce que les poireaux soient parfaitement fondus et qu'il ne reste plus d'eau. Éventuellement, égoutter après cuisson pour retirer l'excédent d'eau.\", 
                            \"Ajouter dans la poêle la crème fraîche, le sel, le poivre, ainsi qu'une bonne pincée de muscade. Réserver au chaud.\", 
                            \"Pendant la cuisson des poireaux, nettoyer les coquilles Saint Jacques. Les éponger sur du papier absorbant. Les poivrer légèrement sur les deux faces et les passer à la farine.\", 
                            \"Faire chauffer l'huile dans une poêle et faire revenir rapidement les coquilles sur chaque face.\", 
                            \"Étaler la fondue de poireaux bien chaude dans 4 assiettes et disposer les noix de Saint Jacques dessus. Servir aussitôt.\"
                        ]
                    }
                    ```";**/

       $jsonString = trim($result, "```json \n");
       $data = json_decode($jsonString, true);

        $data['description'] = "Un délicieux plat principal de filet mignon garni de roquefort et arrosé de Porto, parfait pour impressionner vos invités.";
        $data['tags'] = "filet mignon, roquefort, porto, viande, plat principal";
        $data['category'] = "plat";

       return $this->createRecetteFromData($data);

    }
}
