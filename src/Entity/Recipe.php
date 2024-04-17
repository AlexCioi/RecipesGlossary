<?php

namespace App\Entity;

class Recipe
{
    private array $nodes;
    private array $rels;
    private array $ids;
    private array $ingredients;

    public function __construct(array $nodes, array $rels, array $ids, array $ingredients)
    {
        $this->nodes = $nodes;
        $this->rels = $rels;
        $this->ids = $ids;
        $this->ingredients = $ingredients;
    }

    public function setNodes(array $nodes): void
    {
        $this->nodes = $nodes;
    }

    public function setRels(array $rels): void
    {
        $this->rels = $rels;
    }

    public function setIds(array $ids): void
    {
        $this->ids = $ids;
    }

    public function setIngredients(array $ingredients): void
    {
        $this->ingredients = $ingredients;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function getRels(): array
    {
        return $this->rels;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }
}