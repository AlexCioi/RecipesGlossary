<?php

namespace App\Controller;

use App\Service\BoltManager;
use App\Service\QueryBuilder;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class RecipeController extends AbstractController
{
    public function fetchAllRecipes(
        #[MapQueryParameter] int $pageNumber, #[MapQueryParameter] ?string $name, #[MapQueryParameter] ?array $ingredients,
        #[MapQueryParameter] string $direction, #[MapQueryParameter] string $criterion,
        BoltManager $bolt, SerializerService $serializerService, QueryBuilder $builder): Response
    {

        $query = $builder->returnRecipeResultQuery($pageNumber, $name, $ingredients, $direction, $criterion);

        $boltRecipesResponse = $bolt->runQuery($query);
        $nodeArray = $bolt->boltResponseHandler($boltRecipesResponse);

        $response = $serializerService->arraySerialize($nodeArray);

        return new Response($response, Response::HTTP_OK);
    }

    public function fetchAuthorRecipes(#[MapQueryParameter] string $authorName, SerializerService $serializer, BoltManager $bolt, QueryBuilder $builder): Response
    {
        $query = $builder->returnAuthorRecipesQuery($authorName);

        $boltResponse = $bolt->runQuery($query);
        $nodeArray = $bolt->boltResponseHandler($boltResponse);

        $response = $serializer->arraySerialize($nodeArray[0][0]);

        return new Response($response, Response::HTTP_OK);
    }

    public function fetchRecipeDetails(#[MapQueryParameter] string $recipeId, BoltManager $bolt, SerializerService $serializer, QueryBuilder $builder): Response
    {
        $query = $builder->returnRecipeDetailsQuery($recipeId);

        $boltResponse = $bolt->runQuery($query);
        $nodeArray = $bolt->boltResponseHandler($boltResponse);

        $response = $serializer->arraySerialize($nodeArray[0]);

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

    public function fetchComplexRecipes(BoltManager $bolt, SerializerService $service, QueryBuilder $builder): Response
    {
        $query = $builder->returnComplexRecipes();

        $boltResponse = $bolt->runQuery($query);
        $nodeArray = $bolt->boltResponseHandler($boltResponse);

        $response = $service->arraySerialize($nodeArray);

        return new Response($response, Response::HTTP_OK);
    }
}
