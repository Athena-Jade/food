<?php                                  //FORMULAIRE DE RECHERCHE PAR CATEGORIE OU PAR NOM DU PRODUIT j'ai créé ce formulaire à la main

namespace App\Form;                                 

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;



class SearchType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)   
    {
        $builder
            ->add('string', TextType::class,[    //représente la recherche par texte
                'label'=>false,                 //false pour ne pas mettre de label
                'required'=>false,               //false car cette recherche peut se faire par texte ou par catégorie 
                'attr'=>[
                    'placeholder'=>'Votre recherche...',
                    'class'=>'form-control-sm ml-3'  //réduit la police du mot recherche   ml=margin left
                ]
            ])
           

            ->add('categories',EntityType::class,[
                'label' =>false,
                'required'=>false,  //false car cette recherche peut se faire par texte ou par catégorie 
                'class'=> Category::class,  //faire le lien avec la classe categoy
                'multiple'=> true,  //permet de choisir plusieurs valeurs
                'expanded'=> true   //permet d'avoir une vue en check box pour avoir plusieurs valeurs

            ])   
            
            
            ->add('submit', SubmitType::class,[
                'label'=>'Valider',
                'attr'=>[
                   //  'class'=>'btn-block btn-secondary ml-3'  //   ml-3     bouton filtrer ml-3 = margin left 3
                   'class'=>'btn btn-secondary btn-sm' 
                    
                    
                ]
                    
            ])
        ;
    }
    
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,    
            'method' =>'GET',           //GET pour que les données du formulaire transitent par url cela permet aux users de copier coller les urls et de les partager
            'crsf_protection' =>false,  // Désactivation de crsf car ce formulaire de recherche n'a pas besoin de sécurité
        ]);
    }



    public function getBlockPrefix()
    {
        return '';
    }




}
