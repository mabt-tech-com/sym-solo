<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarDetailController extends AbstractController
{

    /**
     * Display the car details page.
     *
     * @Route("/detail", name="car_detail")
     *
     * @return Response
     */


    #[Route('/detail', name: 'car_detail')]
    public function index(): Response
    {
        return $this->render('detail.html.twig', [
            'controller_name' => 'CarDetailController',
        ]);
    }


}