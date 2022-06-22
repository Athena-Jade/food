<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    //1) appeler entityManager de Doctrine pour faire requête sql pour récupérer mes produits dans la bdd
    private $entitymanager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response
    {
        //2) faire une requête sql pour recuperer tous mes produits qui sont dans la bdd  
        //dd($products);
       
        //2) instance du formulaire SearchType
        $search = new Search();

        // 1) formulaire SearchType
        $form = $this->createForm(SearchType::class, $search);

        // 4)formulaire SearchType
        $form->handleRequest($request);

        //5) formulaire si soumission
        if ($form->isSubmitted() && $form->isValid()){
           // $search = $form->getData();
            //dd($search);
        
            $products = $this->entitymanager->getRepository(Product::class)->findWithSearch($search);                    
        
        }else{
            $products = $this->entitymanager->getRepository(Product::class)->findAll();
        }


        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
            'form'=> $form->createView()  // 3) mettre formulaire à la vue
        ]);
    
    }



    // la route pour voir le detail du produit
    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {
        //dd($slug);
        //1) faire une requête sql pour recuperer un produit 
        $product = $this->entitymanager->getRepository(Product::class)->findOneBySlug($slug);
       // dd($product);

        
        //si le produit n'existe pas! Alors redirection à la page produits
         if(!$product){
            return $this->redirectToRoute('products');
                
        }

        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product
        ]);
    
    }

}
