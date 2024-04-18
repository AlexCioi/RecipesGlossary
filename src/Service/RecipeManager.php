<?php

namespace App\Service;

use App\Entity\Recipe;

class RecipeManager
{
    public function createRecipeObject(object $boltObject, array $ingredients): Recipe
    {
        return new Recipe($boltObject->nodes, $boltObject->rels, $boltObject->ids, $ingredients, sizeof($ingredients));
    }

    public function getRecipeId(object $boltObject): int
    {
        $recipeId = $boltObject->nodes[1]->properties['id'];

        return $recipeId;
    }
}
