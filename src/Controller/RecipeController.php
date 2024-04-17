<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Service\BoltManager;
use App\Service\RecipeManager;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends AbstractController
{

    public function fetchRecipes(Request $request, BoltManager $bolt, SerializerService $serializerService, RecipeManager $recipeManager): Response
    {
        $boltRecipesResponse = $bolt->runQuery('MATCH p=()-[r:WROTE]->() RETURN p LIMIT 2');

        $recipesArray = $bolt->boltResponseHandler($boltRecipesResponse);
        $recipeObjectArray = [];
        foreach ($recipesArray as $recipe) {

            $recipeId = $recipeManager->getRecipeId($recipe);
            $boltIngredientsResponse = $bolt->runQuery('MATCH (r:Recipe {id: "'.$recipeId.'"})-[:CONTAINS_INGREDIENT]->(i:Ingredient) RETURN i');
            $ingredientsArray = $bolt->boltResponseHandler($boltIngredientsResponse);

            $newRecipeObject = $recipeManager->createRecipeObject($recipe, $ingredientsArray);
            $recipeObjectArray[] = $newRecipeObject;
        }

        $response = $serializerService->arraySerialize($recipeObjectArray);

        return new Response($response, Response::HTTP_OK);
    }
}
