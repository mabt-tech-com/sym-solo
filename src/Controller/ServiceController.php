<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{


    #[Route('/service', name: 'service')]
    public function index(): Response
    {
        /* return $this->render('service.html.twig', [
            'controller_name' => 'ServiceController',
        ]); */

        return $this->json([
            'controller_name' => 'ServiceController',
            'message' => 'Service page',
        ]);
    }



}