<?php  // l'user modifie son mot de passe

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
   //14) appeler entityManager de Doctrine
   private $entityManager;
   public function __construct(EntityManagerInterface $entityManager)
   {
       $this->entityManager = $entityManager;
   }
   
    #[Route('/compte/modifier_mot_passe', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response //4 mettre la request en injection de dépendance.  7) mettre la methode UserPasswordHasherInterface
    {
        $notification = null;

        //2) récuper l'user courant, celui qui est connecté
        $user = $this->getUser();
        
        //1) appeler le formulaire 
        $form = $this->createForm(ChangePasswordType::class, $user);

        //3) traiter le formulaire
        $form->handleRequest($request);

        //5) si le formulaire est soumit et valide
        if($form->isSubmitted() && $form->isValid()){
            
            //9) recuperer old_password
            $old_pwd = $form->get('old_password')->getData();

           //10) dd($old_pwd); // pour voir l'ancien mot passe

            //8 modifier le mot passe
            if($encoder->isPasswordValid($user, $old_pwd)){
             
                //11) nouveau mot passe
                $new_pwd = $form->get('new_password')->getData();

                //dd($new_pwd); // pour voir le nouveau mot passe

                //12) encoder le mot passe
                $password = $encoder->hashPassword($user, $new_pwd);

                //13) récuperer nouveau mot passe
                $user->setPassword($password);

                //15) enregistrer en bdd avec entityManager de Doctrine
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $notification ="votre mot de passe a bien été mis à jour";
            
            }else{
                $notification ="Votre mot de passe actuel n'est pas le bon"; 
            }



        }
        
        
        return $this->render('account/password.html.twig', [
            'controller_name' => 'AccountPasswordController',
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }




}
