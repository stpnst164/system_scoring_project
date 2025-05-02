<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Service\ScoringService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClientController extends AbstractController
{
    #[Route('/clients', name: 'app_client', methods: ['GET'])]
    public function index(
        Request $request,
        ClientRepository $clientRepository,
        ScoringService $scoringService,
        PaginatorInterface $paginator): Response
    {
        //Список клиентов
        $clients = $clientRepository -> findAll();

        //Подсчет очков клиента
        $scoring = [];
        foreach ($clients as $client) {
            $scoring[$client -> getId()] = $scoringService -> calculate($client) -> getTotal();
        }

        //Запрос - сортировка по id - от большего к меньшему по скорингу
        $queryBuilder = $clientRepository -> createQueryBuilder('c')
            -> orderBy('c.id', 'DESC')
            -> getQuery();

        //Пагинация
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients' => $clients,
            'scoring' => $scoring,
            'pagination' => $pagination
        ]);
    }
}
