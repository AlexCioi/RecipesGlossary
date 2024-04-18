<?php

namespace App\Service;

class BoltManager
{
    public function runQuery(string $query): \Iterator
    {
        $conn = new \Bolt\connection\Socket('neo4j://34.232.57.230', 7687);
        $bolt = new \Bolt\Bolt($conn);

        $bolt->setProtocolVersions(5.2);
        $protocol = $bolt->build();

        $response = $protocol->hello()->getResponse();
        $response = $protocol->logon(['scheme' => 'basic',
            'principal' => 'neo4j',
            'credentials' => 'internship-2024'])
            ->getResponse();

        $protocol
            ->run($query)
            ->pull();

        $result = $protocol->getResponses();

        return $result;
    }

    public function boltResponseHandler(\Iterator $boltResponse): array
    {
        $boltResponseArray = [];
        foreach ($boltResponse as $responseObject) {
            array_push($boltResponseArray, $responseObject->content);
        }

        array_shift($boltResponseArray);
        array_pop($boltResponseArray);

        return $boltResponseArray;
    }

    public function getNumberOfRecipes(): int
    {
        $recipesNumberResponse = $this->runQuery('
            MATCH (r:Recipe) RETURN COUNT(r)
        ');

        $numberArray = $this->boltResponseHandler($recipesNumberResponse);

        return $numberArray[0][0];
    }
}
