<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientTypeForm;
use App\Service\ScoringService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'client_register')]
    public function register(Request $request, EntityManagerInterface $entityManager,
                            ScoringService $scoringService): Response
    {
        $client = new Client();

        $form = $this->createForm(ClientTypeForm::class, $client);

        $form -> handleRequest($request);


        $scoringResult = null;

        if ($form -> isSubmitted() && $form -> isValid()) {
            $scoringResult = $scoringService -> calculate($client);
            //Начисляются очки клиенту во время регистрации
            $client -> setScoring($scoringResult -> total);

            $entityManager -> persist($client);
            $entityManager -> flush();
        }

        return $this->render('register/register.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form -> createView(),
            'scoringResult' => $scoringResult
        ]);
    }
}
