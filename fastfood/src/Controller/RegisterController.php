<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    //7) appeler l'entityManager de Doctrine
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder ): Response //4) injection de dépendance
    {
        //1) creer 1 user
        $user = new User();

        //2) creer le formulaire
        $form = $this->createForm(RegisterType::class, $user);
        
        // 3) traitement du formulaire
        $form->handleRequest($request);
        
        //5) condition if si le formulaire est soumit et valide
        if($form->isSubmitted() && $form->isValid()){
            
            $user = $form->getData(); //récupération de toutes les données du formulaire

            //encoder le mot de passe de l'user
            $password = $encoder->hashPassword($user, $user->getPassword());
           // dd($password);

            $user->setPassword($password);

            //dd($user); //6)  pour voir ce que contient le formulaire, une fois que l'user a tapé les coordonées de son inscription
        
            
            //8) enregistrer l'inscription de l'user dans la bdd
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form->createView()
        ]);
    }
}
