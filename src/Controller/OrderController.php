<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Entity\OrderItem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


final class OrderController extends AbstractController
{


    /**
     * Display the order list page.
     *
     * @Route("/order", name="app_order", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/order', name: 'app_order')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les commandes depuis la base de données
        $orders = $entityManager->getRepository(Order::class)->findAll();

        // Passer les commandes au template Twig
        return $this->render('order/index.html.twig', [
            'orders' => $orders, // Ajouter cette ligne
        ]);

    }



    /**
     * Display a single order.
     *
     * @Route("/order/{id}", name="app_order_show", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/order/{id}', name: 'app_order_show')]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }




    /**
    * Handle the checkout process.
    *
    * @Route("/checkout", name="app_order_checkout", methods={"GET", "POST"})
    *
    * @return Response
    */
    // not a good practice to make 1 function handle lots of calculations, should devide it into smaller functions that are re-usable/responsible for a single task
    #[Route('/checkout', name: 'app_order_checkout')]
    public function checkout(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le panier depuis la session
        $cart = $session->get('cart', []);

        // Vérifier si le panier est vide
        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide. Ajoutez des produits avant de passer une commande.');
            return $this->redirectToRoute('cart_index');
        }

        // Créer une nouvelle commande
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $total = 0;

            // Ajouter chaque produit du panier à la commande
            foreach ($cart as $productId => $quantity) {
                $product = $entityManager->getRepository(Product::class)->find($productId);
                if (!$product) {
                    continue;
                }

                // Créer un nouvel OrderItem
                $orderItem = new OrderItem();
                $orderItem->setOrderReference($order);
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                $orderItem->setPrix($product->getPrix());

                // Persister l'OrderItem
                $entityManager->persist($orderItem);
                $total += $product->getPrix() * $quantity;
            }

            // Définir les propriétés de la commande
            $order->setTotal($total);
            $order->setStatus('pending');
            $order->setCreatedAt(new \DateTimeImmutable());

            // Persister la commande
            $entityManager->persist($order);
            $entityManager->flush();

            // Vider le panier après la commande
            $session->remove('cart');

            // Rediriger vers une page de succès
            $this->addFlash('success', 'Votre commande a été passée avec succès.');
            return $this->redirectToRoute('app_order_success');
        }

        // Afficher le formulaire de commande
        return $this->render('order/checkout.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /**
     * Display the order success page.
     *
     * @Route("/order/success", name="app_order_success", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/order/success', name: 'app_order_success')]
    public function orderSuccess(): Response
    {
        return $this->render('order/success.html.twig');
    }

}
