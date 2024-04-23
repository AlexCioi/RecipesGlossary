<?php

namespace App\Controller;

use App\Service\BoltManager;
use App\Service\PaginationService;
use App\Service\QueryBuilder;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class RecipeController extends AbstractController
{
    public function fetchAllRecipes(
        #[MapQueryParameter] int $pageNumber, #[MapQueryParameter] ?string $name, #[MapQueryParameter] ?array $ingredients,
        Request $request, BoltManager $bolt, SerializerService $serializerService, QueryBuilder $builder): Response
    {

        $query = $builder->returnRecipeResultQuery($pageNumber, $name, $ingredients);
        $boltRecipesResponse = $bolt->runQuery($query);

        $nodeArray = $bolt->boltResponseHandler($boltRecipesResponse);
        dump($nodeArray, $query);

        $response = $serializerService->arraySerialize($nodeArray);
        //dump($skip, $limit, $pageNumber, $ingredientsFilterString, $nodeArray);
        return new Response($response, Response::HTTP_OK);
    }

    public function fetchNumberOfPages(
        #[MapQueryParameter] ?string $name, #[MapQueryParameter] ?array $ingredients,
        BoltManager $bolt, SerializerService $serializer, QueryBuilder $builder): Response
    {
        $query = $builder->returnRecipeCountQuery($name, $ingredients);
        $boltResponse = $bolt->runQuery($query);

        $nodeArray = $bolt->boltResponseHandler($boltResponse);
        $responseArray = array(
            "numberOfRecipes" => $nodeArray[0][0],
        );

        $response = $serializer->arraySerialize($responseArray);

        return new Response($response, Response::HTTP_OK);
    }

    public function fetchRecipesByName(BoltManager $bolt, SerializerService $serializer, PaginationService $paginator): Response
    {
//        $queryBody = '
//            OPTIONAL MATCH (r)-[:COLLECTION]->(c:Collection)
//            OPTIONAL MATCH (r)-[:DIET_TYPE]->(d:DietType)
//            OPTIONAL MATCH (r)-[:KEYWORD]->(k:Keyword)
//            RETURN a, r, COLLECT(DISTINCT i) as ingredients, COLLECT(DISTINCT c) as collections, COLLECT(DISTINCT d) as dietTypes,
//            COLLECT(DISTINCT k) as keywords
//            SKIP ' . $skip . ' LIMIT ' . $limit .'
//        ';
    }


}
