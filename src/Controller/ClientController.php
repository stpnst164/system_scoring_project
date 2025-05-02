<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientTypeForm;
use App\Repository\ClientRepository;
use App\Service\ScoringService;
use Doctrine\ORM\EntityManagerInterface;
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

    //Карточка клиента
    #[Route('/clients/{id}, name: app_client_show', methods: ['GET'])]
    public function show(Client $client): Response {
        return $this -> render('client/show.html.twig', [
            'client' => $client
        ]);
    }

    //Редактирование карточки клиента
    #[Route('/clients/{id}/edit, name: app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Client $client,
                        Request $request,
                        EntityManagerInterface $entityManager): Response {

        $form = $this->createForm(ClientTypeForm::class, $client);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $entityManager -> flush();
            return $this -> redirectToRoute('app_client');
        }

        return $this -> render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form -> createView()
        ]);
    }
}
