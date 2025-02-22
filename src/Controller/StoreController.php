<?php

namespace App\Controller;

use App\Entity\Store;
use App\Form\StoreType;
use App\Repository\StoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/store')]
final class StoreController extends AbstractController
{



    #[Route(name: 'app_store_index', methods: ['GET'])]
    public function index(StoreRepository $storeRepository): Response
    {
        /* return $this->render('store/index.html.twig', [
            'stores' => $storeRepository->findAll(),
        ]); */

        return $this->json([
            'stores' => $storeRepository->findAll(),
        ]);
    }


     /**
     *
     * @Route("/new", name="app_store_new", methods={"GET", "POST"})
     *
     * @return Response
     */
    #[Route('/new', name: 'app_store_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $store = new Store();
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($store);
            $entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Store created successfully',
                'store' => $store,
            ]);
        }

        /* return $this->render('store/new.html.twig', [
            'store' => $store,
            'form' => $form,
        ]); */

        return $this->json([
            'status' => 'error',
        ]);
    }



    /**
     * Display a single store.
     *
     * @Route("/{id}", name="app_store_show", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/{id}', name: 'app_store_show', methods: ['GET'])]
    public function show(Store $store): Response
    {
        /* return $this->render('store/show.html.twig', [
            'store' => $store,
            'products' => $store->getProducts(),
        ]); */

        return $this->json([
            'store' => $store,
            'products' => $store->getProducts(),
        ]);
    }




    /**
     * Edit an existing store.
     *
     * @Route("/{id}/edit", name="app_store_edit", methods={"GET", "POST"})
     *
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_store_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Store $store, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Store updated successfully',
                'store' => $store,
            ]);
        }

        /* return $this->render('store/edit.html.twig', [
            'store' => $store,
            'form' => $form,
        ]); */

        return $this->json([
            'status' => 'error',
            'errors' => (string) $form->getErrors(true, false),
        ]);
    }





    /**
     * Delete a store.
     *
     * @Route("/{id}", name="app_store_delete", methods={"POST"})
     *
     * @return Response
     */
    #[Route('/{id}', name: 'app_store_delete', methods: ['POST'])]
    public function delete(Request $request, Store $store, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $store->getId(), $request->get('_token'))) {
            $entityManager->remove($store);
            $entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Store deleted successfully',
            ]);
        }

        /* return $this->redirectToRoute('app_store_index', [], Response::HTTP_SEE_OTHER); */

        return $this->json([
            'status' => 'error',
        ]);
    }


    

}
