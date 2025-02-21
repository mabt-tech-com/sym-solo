<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarListController extends AbstractController
{


    /**
     * Display the car list page.
     *
     * @Route("/car", name="car_list")
     *
     * @return Response
     */

    #[Route('/car', name: 'car_list')]
    public function index(): Response
    {
        return $this->render('car.html.twig', [
            'controller_name' => 'CarListController',
        ]);
    }


}