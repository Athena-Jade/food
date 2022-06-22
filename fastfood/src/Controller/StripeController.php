<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);


        //si ne trouve pas order
         if(!$order){                                
            return $this->redirectToRoute('order'); 
        }

        // dd($order->getOrderDetails()->getValues());


        //  foreach($cart->getFull() as $product){
        foreach ($order->getOrderDetails()->getValues() as $product) {
            // dd($product);      // la commande                              
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());

            $products_for_stripe[] = [
                'price_data' => [

                    'currency' => 'eur',

                    'unit_amount' => $product->getPrice(),

                    'product_data' => [        //nom du produit

                        'name' => $product->getProduct(),

                        'images' => [$YOUR_DOMAIN . "/upload/".$product_object->getIllustration()],

                    ],

                ],
                'quantity' => $product->getQuantity(),
            ];
        }



        //  dd($products_for_stripe);

        //ajout transporteur.   Enregistrer le transporteur comme s'il s'agit d'un produit
        // $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());   

        $products_for_stripe[] = [
            'price_data' => [

                'currency' => 'eur',

                'unit_amount' => $order->getCarrierPrice() ,

                'product_data' => [

                    'name' => $order->getCarrierName(),

                    'images' => [$YOUR_DOMAIN],

                ],

            ],
            'quantity' => 1,
        ];


        //   dd($products_for_stripe);


        Stripe::setApiKey('sk_test_51LATIqLyM5Z6BVSg3laNnBBKoCsVmef8iaO6InagPI6prQCkbhWFSj6V7OoZfZREFchwiawJTVCZ1ChSHg3AEu6600eQp54l6p');

        //  Session::create();
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [$products_for_stripe],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',


            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);


        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush(); 

        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $this->redirect($checkout_session->url);
     
       
    }


}
