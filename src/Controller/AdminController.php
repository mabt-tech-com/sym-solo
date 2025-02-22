<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/grant-points', methods: ['POST'])]
    public function grantPoints(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $data = json_decode($request->getContent(), true);
        $user = $em->getRepository(User::class)->find($data['user_id']);

        $em->flush();

        return $this->json([
            'status' => 'success',
            'new_balance' => $user->getLoyaltyPoints()
        ]);
    }
}