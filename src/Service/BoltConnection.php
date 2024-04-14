<?php

namespace App\Service;

class BoltConnection
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
}
