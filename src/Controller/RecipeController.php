<?php

namespace App\Controller;

use App\Service\BoltManager;
use App\Service\PaginationService;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class RecipeController extends AbstractController
{
    public function fetchAllRecipes(
        #[MapQueryParameter] int $pageNumber, #[MapQueryParameter] string $name, #[MapQueryParameter] array $ingredients,
        Request $request, BoltManager $bolt, SerializerService $serializerService, PaginationService $paginator): Response
    {
        $skip = ($pageNumber - 1) * 20;
        $limit = 20;

        $queryHeader = 'MATCH (a:Author)-[:WROTE]->(r:Recipe)-[:CONTAINS_INGREDIENT]->(i:Ingredient)';

        $nameQuery = 'WHERE toLower(r.name) CONTAINS toLower("'.$name.'")';

        $ingredientsFilterString = json_encode($ingredients);
        $ingredientFilterQuery = 'WHERE i.name IN'.$ingredientsFilterString;

        $queryBody = '
            OPTIONAL MATCH (r)-[:COLLECTION]->(c:Collection)
            OPTIONAL MATCH (r)-[:DIET_TYPE]->(d:DietType)
            OPTIONAL MATCH (r)-[:KEYWORD]->(k:Keyword)
            RETURN a, r, COLLECT(DISTINCT i) as ingredients, COLLECT(DISTINCT c) as collections, COLLECT(DISTINCT d) as dietTypes,
            COLLECT(DISTINCT k) as keywords
            SKIP ' . $skip . ' LIMIT ' . $limit .'
        ';

        $query = $queryHeader;
        if (count($ingredients) > 1) {
            $query = $query.$ingredientFilterQuery;
        }

        if ($name !== '') {
            $query = $query . $nameQuery;
        }

        $query = $query . $queryBody;

        $boltRecipesResponse = $bolt->runQuery($query);

        dump($query);

        $nodeArray = $bolt->boltResponseHandler($boltRecipesResponse);
        $response = $serializerService->arraySerialize($nodeArray);
        dump($skip, $limit, $pageNumber, $ingredientsFilterString, $nodeArray);
        return new Response($response, Response::HTTP_OK);
    }

    public function fetchRecipesByName(BoltManager $bolt, SerializerService $serializer, PaginationService $paginator): Response
    {
//        '
//            MATCH (a:Author)-[:WROTE]->(r:Recipe)-[:CONTAINS_INGREDIENT]->(i:Ingredient)
//            OPTIONAL MATCH (r)-[:COLLECTION]->(c:Collection)
//            OPTIONAL MATCH (r)-[:DIET_TYPE]->(d:DietType)
//            OPTIONAL MATCH (r)-[:KEYWORD]->(k:Keyword)
//            RETURN a, r, COLLECT(DISTINCT i) as ingredients, COLLECT(DISTINCT c) as collections,
//            COLLECT(DISTINCT d) as dietTypes, COLLECT(DISTINCT k) as keywords
//            SKIP ' . $skip . ' LIMIT ' . $limit . '
//        '
    }

    public function fetchNumberOfPages(BoltManager $bolt, PaginationService $paginator): Response
    {
        $recipesNumber = $bolt->getNumberOfRecipes();
        $pagesNumber = $paginator->getNumberOfPages($recipesNumber, 20);

        return new Response($pagesNumber, Response::HTTP_OK);
    }
}
