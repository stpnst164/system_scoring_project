<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Service\ScoringService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, ScoringService $scoringService): Response
    {
        //Список клиентов
        $clients = $clientRepository -> findAll();

        //Подсчет очков клиента
        $scorings = [];
        foreach ($clients as $client) {
            $scorings[$client -> getId()] = $scoringService -> calculate($client) -> getTotal();
        }

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients,
            'scorings' => $scorings
        ]);
    }
}
