<?php

namespace App\Controller;

use App\Service\BoltManager;
use App\Service\QueryBuilder;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends AbstractController
{
    public function fetchProlificAuthors(BoltManager $bolt, SerializerService $service, QueryBuilder $builder): Response
    {
        $query = $builder->returnProlificAuthors();

        $boltResponse = $bolt->runQuery($query);
        $nodeArray = $bolt->boltResponseHandler($boltResponse);

        $response = $service->arraySerialize($nodeArray);

        return new Response($response, Response::HTTP_OK);
    }
}
