<?php

namespace App\Controller;



use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'order')]
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getuser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }


        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        // $form->handleRequest($request);

        //  if($form->isSubmitted() && $form->isValid()){
        //dd($form->getData());
        //  }


        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }





    // ici je crée ma commande en bdd
    #[Route('/commande/recap', name: 'order_recap')] 
    public function add(Cart $cart, Request $request): Response
    {
        //  if(!$this->getuser()->getAddresses()->getValues()){
        //      return $this->redirectToRoute('account_address_add');
        //  }


        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());

            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();

            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstName() . '' . $delivery->getLastName();  //je mets à l'intérieur de delivery_content (nom, prénom etc..);
            $delivery_content .= '<br/>' . $delivery->getPhone();
            // dd($delivery);
            if ($delivery->getCompany()) {   //si l'user possède une société et a renseigné l'adresse
                $delivery_content .= '<br/>' . $delivery->getCompany();
            }


            $delivery_content .= '<br/>' . $delivery->getAddress();
            $delivery_content .= '<br/>' . $delivery->getPostal() . '' . $delivery->getCity();
            $delivery_content .= '<br/>' . $delivery->getCountry();
            // dd($delivery_content);

            //enregistrer la commande avec Order
            $order = new Order();

            $reference = $date->format('dmy').'-'.uniqid();
            $order->setReference($reference);

            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            //enregistrer en bdd
            $this->entityManager->persist($order);


            //enregistrer les produits avec OrderDetails
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                // dd($product);                                    
                //enregistrer en bdd
                $this->entityManager->persist($orderDetails);
            }


              $this->entityManager->flush();

             //  dd($order);
            

            return $this->render('order/add.html.twig', [
                'controller_name' => 'OrderController',
                
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' =>$order->getReference()
            ]);
        
        }
    
    
        return $this->redirectToRoute('cart');
    
    }

}
