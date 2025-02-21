<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;


#[Route('/reservation', name: 'reservation_')]
class ReservationController extends AbstractController
{


    /**
     * Display the reservation list page.
     *
     * @Route("/", name="index", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, ReservationRepository $reservationRepository): Response
    {
        // Check for any query parameters or other condition for booking view
        $isBooking = $request->query->get('booking', false); // Check if booking is requested

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
            'is_booking' => $isBooking,  // Passing a flag for showing booking view
        ]);
    }


    /**
     * Create a new reservation.
     *
     * @Route("/new", name="new", methods={"GET", "POST"})
     *
     * @return Response
     */
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Display a single reservation.
     *
     * @Route("/{id}", name="show", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }


    /**
     * Edit an existing reservation.
     *
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     *
     * @return Response
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Delete a reservation.
     *
     * @Route("/{id}/delete", name="delete", methods={"POST"})
     *
     * @return Response
     */
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index');
    }


    /**
     * Display the booking page.
     *
     * @Route("/booking", name="booking", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/booking', name: 'booking', methods: ['GET'])]
    public function booking(): Response
    {
        // Logic for car booking page
        return $this->render('reservation/booking.html.twig');
    }

}

