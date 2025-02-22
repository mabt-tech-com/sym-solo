<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationNewController extends AbstractController
{


#[Route('/reservation/new', name: 'app_reservation_new')]
public function index(): Response
{
    /* return $this->render('reservation_new/index.html.twig', [
        'controller_name' => 'ReservationNewController',
    ]); */

    return $this->json([
        'controller_name' => 'ReservationNewController',
        'message' => 'Reservation new page'
    ]);
}

}
