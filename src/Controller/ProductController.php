<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{





    /**
     * Display the product list page.
     *
     * @Route("/", name="app_product_index", methods={"GET"})
     *
     * @return Response
     */

    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        /* return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]); */

        return $this->json([
            'products' => $productRepository->findAll(),
        ]);
    }





    /**
     * Create a new product.
     *
     * @Route("/new", name="app_product_new", methods={"GET", "POST"})
     *
     * @return Response
     */

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $data = json_decode($request->getContent(), true);

        // Manual data assignment
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrix($data['prix']);

        /*   $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $product->setImage($newFilename);
            }*/

           // Enregistrer le produit
            $entityManager->persist($product);
            $entityManager->flush();
/*
            $this->addFlash('success', 'Produit créé avec succès !');
            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }*/

            return $this->json([
                'status' => 'success',
                'id' => $product->getId(),
                'name' => $product->getName()
            ], Response::HTTP_CREATED);
        }




    /**
     * Edit an existing product.
     *
     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     *
     * @return Response
     */

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        /* $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image (si une nouvelle image est uploadée)
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $product->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Produit mis à jour avec succès !');
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]); */

        if ($request->isMethod('GET')) {
            return $this->json([
                'id' => $product->getId(),
                'name' => $product->getName(),
                'prix' => $product->getPrix(),
                'image' => $product->getImage(),
            ]);
        }

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            $form = $this->createForm(ProductType::class, $product);
            $form->submit($data);

            if ($form->isSubmitted() && $form->isValid()) {
                // Handle image upload if a new image is provided
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $product->setImage($newFilename);
                }

                $entityManager->flush();

                return $this->json([
                    'status' => 'success',
                    'message' => 'Product updated successfully!',
                    'product' => [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'description' => $product->getDescription(),
                        'prix' => $product->getPrix(),
                        'image' => $product->getImage(),
                    ]
                ], Response::HTTP_OK);
            }

            return $this->json([
                'status' => 'error',
                'message' => 'Invalid form data',
                'errors' => (string) $form->getErrors(true, false),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'error',
            'message' => 'Invalid request method',
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }








    /**
     * Delete a product.
     *
     * @Route("/{id}", name="app_product_delete", methods={"POST"})
     *
     * @return Response
     */

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        /*
          // UI CSRF Check (commented)
         if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
        */

            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit supprimé avec succès !');
       // }


 /*  return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
  }
 */
        return $this->json(['status' => 'success']);
    }










    /**
     * Display a single product.
     *
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     *
     * @return Response
     */

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        /* return $this->render('product/show.html.twig', [
            'product' => $product,
        ]); */

        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'prix' => $product->getPrix(),
            'image' => $product->getImage(),
        ]);
    }



}
