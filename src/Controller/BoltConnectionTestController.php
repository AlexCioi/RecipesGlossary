<?php

namespace App\Controller;

use Bolt\Bolt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BoltConnectionTestController extends AbstractController
{
    #[Route('/bolt', name: 'app_bolt_connection_test')]
    public function index(): Response
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
            ->run('MATCH (n:DietType) RETURN n LIMIT 25')
            ->pull();

        foreach ($protocol->getResponses() as $response) {
            dump($response);
        }

        return $this->render('bolt_connection_test/index.html.twig', [
            'controller_name' => 'BoltConnectionTestController',
        ]);
    }
}
