<?php

namespace App\Controller;

use App\Classe\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;

class OrderSuccessController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    
    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);  //recuperation de la commande avec la stripeSessionId 
       // dd($order);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
       
       
       
        if(!$order->getIsPaid()){
            //vider la session cart c'est vider le panier de l'user
            $cart->remove();
          
            // modifier le statut is Paid en mettant 1
            $order->setIsPaid(1);

            $this->entityManager->flush();
            
            // envoyer mail à l'user pour confirmer sa commande
        }

                

        return $this->render('order_success/index.html.twig', [
           'order' => $order
          
            
        ]);
    }





}
