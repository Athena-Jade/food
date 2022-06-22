<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'A quel nom, voulez-vous donner à votre adresse?',
                'attr'=> [
                    'placeholder'=>'Nommer votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Votre prénom',
                'attr'=> [
                    'placeholder'=>'Votre prénom'
                ]
            ])
            
            
            
            
            
            ->add('lastname', TextType::class, [
                'label'=>'Votre nom',
                'attr'=> [
                    'placeholder'=>'Votre nom'
                ]
            ])
            
            
            
            
            
            ->add('company', TextType::class, [
                'label'=>'Votre entreprise ',
                'required'=>false,  //le nom de l'entreprise est facultatif
                'attr'=> [
                    'placeholder'=>'(facultatif) Entrer le nom de votre société'
                ]
            ])




            ->add('address', TextType::class, [
                'label'=>'Votre adresse de livraison',
                'attr'=> [
                    'placeholder'=>'Votre adresse de livraison'
                ]
            ])




            ->add('postal', TextType::class, [
                'label'=>'Votre code postal',
                'attr'=> [
                    'placeholder'=>'Votre code postal '
                ]
            ])




            ->add('city', TextType::class, [
                'label'=>'Votre ville',
                'attr'=> [
                    'placeholder'=>'Votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label'=>'Votre pays',
                'attr'=> [
                    'placeholder'=>'Votre pays'
                ]
            ])



            ->add('phone', TelType::class, [
                'label'=>'Votre numéro de téléphone',
                'attr'=> [
                    'placeholder'=> 'Votre numéro de téléphone'
                ]
            ])



            ->add('submit', SubmitType::class, [
                'label'=> 'Valider',
                'attr'=>[
                    'class'=> 'btn block btn-secondary'
                ]
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
