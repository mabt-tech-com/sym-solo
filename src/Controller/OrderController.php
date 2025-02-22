<?php

namespace App\Controller;


use App\Entity\LoyaltySettings;
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
    #[Route('/order', name: 'app_order', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les commandes depuis la base de données
        $orders = $entityManager->getRepository(Order::class)->findAll();

        /* return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]); */

        return $this->json([
            'orders' => $orders,
        ]);
    }





    /**
     * Display a single order.
     *
     * @Route("/order/{id}", name="app_order_show", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/order/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        /* return $this->render('order/show.html.twig', [
            'order' => $order,
        ]); */

        return $this->json([
            'id' => $order->getId(),
            'status' => $order->getStatus(),
            'total' => $order->getTotal(),
            'created_at' => $order->getCreatedAt(),
            'items' => array_map(function($item) {
                return [
                    'product_id' => $item->getProduct()->getId(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrix(),
                ];
            }, $order->getOrderItems()->toArray())
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
        // Get the authenticated user
        $user = $this->getUser();

        // $data is for the json data that is sent from the front end.
        $data = json_decode($request->getContent(), true);
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

        // Calculate total and points
        $total = 0;
        $pointsEarned = 0;

        // Ajouter chaque produit du panier à la commande
        foreach ($cart as $productId => $quantity) {
            $product = $entityManager->getRepository(Product::class)->find($productId);
            if (!$product) {
                continue;
            }

            // Calculate points earned for this product
            $pointsEarned += $product->getProductPoints() * $quantity;

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

        // Handle points redemption if requested
        if (isset($data['use_points'])) {
            $loyaltySettings = $entityManager->getRepository(LoyaltySettings::class)->find(1);
            $maxPoints = min($user->getLoyaltyPoints(), $data['use_points']);
            $discount = $loyaltySettings->convertPointsToMoney($maxPoints);

            $order->setUsedLoyaltyPoints($maxPoints);
            $total = max(0, $total - $discount);
            $user->redeemPoints($maxPoints);
        }

        // Add earned points to user
        $user->addLoyaltyPoints($pointsEarned);

        // Définir les propriétés de la commande
        $order->setTotal($total);
        $order->setStatus('pending');
        $order->setCreatedAt(new \DateTimeImmutable());

        // Persister la commande
        $entityManager->persist($order);
        $entityManager->flush();

        // Vider le panier après la commande
        $session->remove('cart');

        /*
        // Afficher le formulaire de commande
        return $this->render('order/checkout.html.twig', [
            'form' => $form->createView(),
        ]);
        */

        return $this->json([
            'status' => 'success',
            'order_id' => $order->getId(),
            'total' => $order->getTotal(),
            'points_earned' => $pointsEarned,
            'points_remaining' => $user->getLoyaltyPoints()
        ]);
    }




    /**
     * Display the order success page.
     *
     * @Route("/order/success", name="app_order_success", methods={"GET"})
     *
     * @return Response
     */
    #[Route('/order/success', name: 'app_order_success', methods: ['GET'])]
    public function orderSuccess(): Response
    {
        /* return $this->render('order/success.html.twig'); */

        return $this->json([
            'status' => 'success',
            'message' => 'Order completed successfully!'
        ]);
    }

}
