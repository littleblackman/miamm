<?php

namespace App\Domain\Ingredient;

use App\Core\EntityManager;
use PDO;

class IngredientRepository
{
    private PDO $manager;

    public function __construct()
    {
        $this->manager = EntityManager::getConnection();
    }

}
