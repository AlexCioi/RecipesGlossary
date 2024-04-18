<?php

namespace App\Controller;

use App\Service\BoltManager;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends AbstractController
{
    public function fetchRecipes(Request $request, BoltManager $bolt, SerializerService $serializerService): Response
    {
        $boltRecipesResponse = $bolt->runQuery(
            'MATCH (a:Author)-[:WROTE]->(r:Recipe)-[:CONTAINS_INGREDIENT]->(i:Ingredient) RETURN a, r, COLLECT(i) AS ingredients LIMIT 100'
        );

        $nodeArray = $bolt->boltResponseHandler($boltRecipesResponse);
        $response = $serializerService->arraySerialize($nodeArray);

        return new Response($response, Response::HTTP_OK);
    }
}
