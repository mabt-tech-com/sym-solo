<?php

// src/Controller/CartController.php
namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{


    /**
     * Display the cart with all products and total price.
     *
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */

    #[Route('/', name: 'cart_index', methods: ['GET'])]
    public function index(SessionInterface $session, ProductRepository $productRepository): JsonResponse
    {
        // Récupérer le panier depuis la session
        $cart = $session->get('cart', []);

        // Préparer les données des produits du panier
        $products = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
                $total += $product->getPrix() * $quantity;
            }
        }


        return new JsonResponse([
            'products' => $products,
            'total' => $total,
        ]);
       /* return $this->render('cart/index.html.twig', [
            'products' => $products,
            'total' => $total,
        ]);*/
    }




    /**
     * Add a product to the cart.
     *
     * @param Product $product
     * @param SessionInterface $session
     * @return Response
     */

    // Change from GET to POST for add/remove/clear actions
    #[Route('/add/{id}', name: 'cart_add', methods: ['POST'])]  // Changed from GET
    public function add(Product $product, SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session
        $cart = $session->get('cart', []);

        // Ajouter ou mettre à jour la quantité du produit dans le panier
        $id = $product->getId();
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        // Mettre à jour le panier dans la session
        $session->set('cart', $cart);

        // Message de succès
        $this->addFlash('success', 'Le produit a été ajouté au panier avec succès.');

        // Rediriger vers la liste des produits
        return $this->redirectToRoute('app_product_index');
    }









    /**
     * Remove a product from the cart.
     *
     * @param Product $product
     * @param SessionInterface $session
     * @return Response
     */
    #[Route('/remove/{id}', name: 'cart_remove', methods: ['POST'])]  // Changed from GET
    public function remove(Product $product, SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session
        $cart = $session->get('cart', []);

        // Supprimer le produit du panier
        $id = $product->getId();
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        // Mettre à jour le panier dans la session
        $session->set('cart', $cart);

        // Message de succès
        $this->addFlash('success', 'Le produit a été retiré du panier avec succès.');

        // Rediriger vers le panier
        return $this->redirectToRoute('cart_index');
    }









    /**
     * Clear the cart.
     *
     * @param SessionInterface $session
     * @return Response
     */
    #[Route('/clear', name: 'cart_clear', methods: ['POST'])]  // Changed from GET
    public function clearCart(SessionInterface $session): Response
    {
        // Supprimer le panier de la session
        $session->remove('cart');

        // Ajouter un message flash pour informer l'utilisateur
        $this->addFlash('success', 'Votre panier a été vidé.');

        // Rediriger vers la page du panier
        return $this->redirectToRoute('cart_index');
    }




}
