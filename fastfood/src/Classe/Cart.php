<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }
    
        
    
    public function add($id){

        $cart = $this-> session->get('cart', []);

        if (!empty($cart[$id])) {
            
            $cart[$id]++;

        }else{
            $cart[$id] = 1;
        }

        $this->session->set('cart',$cart );

    }

    
    public function get()
    {        
        return $this->session->get('cart');
    }



    
    // supprimer l'ensemble du panier
    public function remove()
    {
        return $this->session->remove('cart');
    }



    
    // supprimer 1 produit du panier
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        
        //retirer
        unset($cart[$id]);

        return $this->session->set('cart', $cart);
   
    }



    // diminuer une quantité
    public function decrease($id){
        //1) récupérer la session
        $cart = $this->session->get('cart', []);


        //2) vérifier que la quantité n'est pas égale à 1
        if ($cart[$id] > 1) {
            
            //3) retirer le produit
            $cart[$id]--;

        }else{
            //4) supprimer le produit
            unset($cart[$id]);
        }


        return $this->session->set('cart', $cart);

    }


    public function getFull(){
        
        $cartComplete = [];

        if ($this->get()) {

            foreach ($this->get() as $id => $quantity) {
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                
                if (!$product_object) {
                    $this->delete($id);
                    continue;
                }
               
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
    
        return $cartComplete;
    
    }






}