<?php

namespace App\Controller;

use App\Service\BoltManager;
use App\Service\QueryBuilder;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IngredientController extends AbstractController
{
    public function fetchAllIngredients(Request $request, BoltManager $bolt, SerializerService $service): Response
    {
        $boltResponse = $bolt->runQuery('MATCH (n:Ingredient) RETURN n');

        $ingredientsArray = $bolt->boltResponseHandler($boltResponse);
        $response = $service->arraySerialize($ingredientsArray);

        //dd($ingredientsArray);

        return new Response($response, Response::HTTP_OK);
    }

    public function fetchTopIngredients(BoltManager $bolt, SerializerService $service, QueryBuilder $builder): Response
    {
        $query = $builder->returnTopIngredients();

        $boltResponse = $bolt->runQuery($query);
        $nodeArray = $bolt->boltResponseHandler($boltResponse);

        $response = $service->arraySerialize($nodeArray);

        return new Response($response, Response::HTTP_OK);
    }
}
