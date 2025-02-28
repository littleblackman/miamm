<?php

namespace App\Domain\Recette;

use App\Core\EntityManager;
use PDO;

class RecetteRepository {
    private PDO $manager;

    public function __construct()
    {
        $this->manager = EntityManager::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->manager->query("SELECT * FROM recette");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Recette($row), $data);
    }

    public function findById(int $id): ?Recette
    {
        $stmt = $this->manager->prepare("SELECT * FROM recette WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$data) return null;
        $recette = new Recette($data);

        $stmt = $this->manager->prepare("
            SELECT i.*, ri.quantity, ri.unit 
            FROM ingredient i 
            JOIN recette_ingredient ri ON i.id = ri.ingredient_id 
            WHERE ri.recette_id = :id
        ");
        $stmt->execute(['id' => $id]);
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $recette->setIngredients($ingredients);

        return $recette;
    }

    public function findLatest($nb): array
    {
        $stmt = $this->manager->query("SELECT * FROM recette ORDER BY created_at DESC LIMIT ".$nb);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Recette($row), $data);
    }


    public function delete($id): bool
    {
        $sql = "DELETE FROM recette WHERE id = :id";
        $joins = "DELETE FROM recette_ingredient WHERE recette_id = :id";

        $stmt = $this->manager->prepare($sql);
        $stmt->execute(['id' => $id]);

        $stmt = $this->manager->prepare($joins);
        $stmt->execute(['id' => $id]);

        return true;

    }

    public function save(Recette $recette): Recette
    {

        $data = get_object_vars($recette);
        unset($data['ingredients']);

        foreach($data as $key => $value) {
            $update[] = $key.'=:'.$key;
            $insert[] = $key;
            $values[] = ':'.$key;
        }

        if($recette->getId()) {
            $data['id'] = $recette->getId();
            $sql = 'UPDATE recette SET '.implode(",", $update).' WHERE id = :id';
        } else {
            $sql = 'INSERT INTO recette ('.implode(",", $insert).') VALUES ('.implode(',', $values).')';
        }

        $stmt = $this->manager->prepare($sql);
        $stmt->execute($data);

        if (!$recette->getId()) {
            $id = $this->manager->lastInsertId();
            $recette->setId($id);
        }
        return $recette;
    }

    public function addIngredient(int $recetteId, array $ingredient): bool
    {
        // on vérifie si ingredients existe
        $sql = "SELECT * FROM ingredient WHERE name = :name";
        $stmt = $this->manager->prepare($sql);
        $stmt->execute(['name' => $ingredient['name']]);

        if($stmt->rowCount() > 0) {
            $ingredientId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        } else {
            // on ajoute l'ingredient
            $sql = "INSERT INTO ingredient (name) VALUES (:name)";
            $stmt = $this->manager->prepare($sql);
            $stmt->execute(['name' => $ingredient['name']]);
            $ingredientId = $this->manager->lastInsertId();
        }

        // on ajoute la jointure
        $sql = "INSERT INTO recette_ingredient (recette_id, ingredient_id, quantity, unit) VALUES (:recette_id, :ingredient_id, :quantity, :unit)";
        $stmt = $this->manager->prepare($sql);
        $stmt->execute([
            'recette_id' => $recetteId,
            'ingredient_id' => $ingredientId,
            'quantity' => $ingredient['quantity'] ?? null,
            'unit' => $ingredient['unit'] ?? null
        ]);

        return true;
    }
}
