<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{
    public function boltResponseSerialize(\Iterator $boltResponse): string
    {
        $boltResponseArray = [];
        foreach ($boltResponse as $responseObject) {
            array_push($boltResponseArray, $responseObject->content);
        }

        array_shift($boltResponseArray);
        array_pop($boltResponseArray);

        $recipesArray = [];
        foreach($boltResponseArray as $recipeNodeObject) {
            $recipesArray[] = $recipeNodeObject[0];
        }

        //dd($recipesArray);

        $jsonEncoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $jsonEncoder);

        $response = $serializer->serialize($recipesArray, 'json');

        return $response;
    }
}
