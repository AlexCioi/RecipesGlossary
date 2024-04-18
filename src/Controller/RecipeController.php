<?php

namespace App\Controller;

use App\Service\BoltManager;
use App\Service\PaginationService;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends AbstractController
{
    public function fetchRecipes(Request $request, BoltManager $bolt, SerializerService $serializerService, PaginationService $paginator): Response
    {
        $boltRecipesResponse = $bolt->runQuery(
            'MATCH (a:Author)-[:WROTE]->(r:Recipe)-[:CONTAINS_INGREDIENT]->(i:Ingredient) RETURN a, r, COLLECT(i) AS ingredients  SKIP 5 LIMIT 10'
        );

        $nodeArray = $bolt->boltResponseHandler($boltRecipesResponse);
        $response = $serializerService->arraySerialize($nodeArray);

        dd($nodeArray);
        return new Response($response, Response::HTTP_OK);
    }

    public function fetchNumberOfPages(BoltManager $bolt, PaginationService $paginator): Response
    {
        $recipesNumber = $bolt->getNumberOfRecipes();
        $pagesNumber = $paginator->getNumberOfPages($recipesNumber, 20);

        return new Response($pagesNumber, Response::HTTP_OK);
    }
}
