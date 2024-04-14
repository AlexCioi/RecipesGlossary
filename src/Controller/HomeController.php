<?php

namespace App\Controller;

use App\Service\BoltConnection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, BoltConnection $boltConnection): Response
    {
        $bolt = new BoltConnection();
        $boltResponse = $bolt->runQuery('MATCH (n:Recipe) RETURN n LIMIT 25');

        foreach ($boltResponse as $diet) {
            dump($diet);
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
