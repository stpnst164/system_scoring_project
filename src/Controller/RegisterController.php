<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'client_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();

        $form = $this->createForm(ClientTypeForm::class, $client);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $entityManager -> persist($client);
            $entityManager -> flush();
        }

        return $this->render('client/register.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form -> createView(),
        ]);
    }
}
