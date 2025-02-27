<?php

namespace App\Domain\Recette;

use App\Core\Trait\Hydrator;

class Recette {

    use Hydrator;

    private ?int $id = null;
    public string $title;
    public string $description;
    public string $category;

    public string $time_total;

    public string $time_preparation;

    public string $time_repos;

    public string $time_cuisson;

    public string $difficulty;

    public string $cost;

    public string $steps;

    public array $ingredients = [];
    public string $createdAt;

    public function __construct($array = null)
    {
        if ($array) $this->hydrate($array);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getTimeTotal(): string
    {
        return $this->time_total;
    }

    public function setTimeTotal(string $time_total): void
    {
        $this->time_total = $time_total;
    }

    public function getTimePreparation(): string
    {
        return $this->time_preparation;
    }

    public function setTimePreparation(string $time_preparation): void
    {
        $this->time_preparation = $time_preparation;
    }

    public function getTimeRepos(): string
    {
        return $this->time_repos;
    }

    public function setTimeRepos(string $time_repos): void
    {
        $this->time_repos = $time_repos;
    }

    public function getTimeCuisson(): string
    {
        return $this->time_cuisson;
    }

    public function setTimeCuisson(string $time_cuisson): void
    {
        $this->time_cuisson = $time_cuisson;
    }

    public function getDifficulty(): string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    public function getCost(): string
    {
        return $this->cost;
    }

    public function setCost(string $cost): void
    {
        $this->cost = $cost;
    }

    public function getSteps(): array
    {
        return json_decode($this->steps, true) ?? [];
    }

    /**
     * set steps with array or json
     * @param string|array $steps
     * @return Object
     */
    public function setSteps(string|array $steps): Object
    {
        // check if array
        if (is_array($steps)) {
            $steps = json_encode($steps, JSON_UNESCAPED_UNICODE);
        }
        $this->steps = $steps;
        return $this;
    }

    public function setIngredients(array $ingredients): void
    {
        $this->ingredients = $ingredients;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

}
