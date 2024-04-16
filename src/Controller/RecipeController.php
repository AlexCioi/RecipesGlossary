<?php

namespace App\Controller;

use App\Service\BoltService;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends AbstractController
{

    public function fetchRecipes(Request $request, BoltService $bolt, SerializerService $serializerService): Response
    {
        //$boltResponse = $bolt->runQuery('MATCH (n:Recipe) RETURN n LIMIT 25');
        $boltResponse = $bolt->runQuery('MATCH p=()-[r:WROTE]->() RETURN p LIMIT 150');

        $recipesArray = $bolt->boltResponseHandler($boltResponse);
        $response = $serializerService->arraySerialize($recipesArray);

        //dd($recipesArray);

        return new Response($response, Response::HTTP_OK);
    }
}
