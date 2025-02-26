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

        return $data ? new Recette($data['title'], $data['description'], $data['category']) : null;
    }

    public function save(Recette $recette): bool
    {
        $stmt = $this->manager->prepare("INSERT INTO recette (title, description, category) VALUES (:title, :description, :category)");
        return $stmt->execute([
            'title' => $recette->getTitle(),
            'description' => $recette->getDescription(),
            'categorie' => $recette->getCategory()
        ]);
    }
}
