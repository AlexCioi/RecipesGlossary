<?php

namespace App\Controller;

use App\Service\BoltManager;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IngredientController extends AbstractController
{
    public function fetchIngredients(int $recipeId, Request $request, BoltManager $bolt, SerializerService $service): Response
    {
        $boltResponse = $bolt->runQuery('MATCH (r:Recipe {id: "'.$recipeId.'"})-[:CONTAINS_INGREDIENT]->(i:Ingredient) RETURN i');

        $ingredientsArray = $bolt->boltResponseHandler($boltResponse);
        $response = $service->arraySerialize($ingredientsArray);

        //dd($ingredientsArray);

        return new Response($response, Response::HTTP_OK);
    }
}