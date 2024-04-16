<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{
    public function arraySerialize(array $array): string
    {
        //dd($recipesArray);

        $jsonEncoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $jsonEncoder);

        $response = $serializer->serialize($array, 'json');

        return $response;
    }
}
