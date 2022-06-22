<?php       // A REFAIRE   ne fonctionne pas !!!!

namespace App\Controller; // permet d'afficher la commande aux users

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;

class AccountOrderController extends AbstractController
{
   private $entityManager;
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entitymanager = $entityManager;
        }

   
    #[Route('/compte/mes-commandes', name: 'account_order')]
    public function index(): Response
    {
      // $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser);
        //dd($orders);
        
        return $this->render('account/order.html.twig', [
            'controller_name' => 'AccountOrderController',
           'orders' =>$orders
        ]);
    }
}
