<?php

namespace App\Controller;

use App\Service\BoltConnection;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends AbstractController
{

    public function fetchRecipes(Request $request, BoltConnection $bolt, SerializerService $serializerService): Response
    {
        $boltResponse = $bolt->runQuery('MATCH (n:Recipe) RETURN n LIMIT 25');

        $response = $serializerService->boltResponseSerialize($boltResponse);
        //dd($response);

        return new Response($response, Response::HTTP_OK);
    }
}
