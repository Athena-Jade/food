<?php

namespace App\Controller\Admin;

use App\Entity\Order;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail');  //permet d'afficher le détail de la commande
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }


    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
           
          //  DateField::new('createdAt', 'passé le')->setFormat('dd/MM/yyyy à H:mm:ss'),  // ne fonctionne pas 
         //DateTimeField::new('createdAt', 'passé le')->setFormat('dd/MM/yyyy à H:mm:ss'), // ne fonctionne pas
         TextField::new('user.FullName', 'Clients'),     //création function getFullName sur entity User pour avoir firstname et lastname en même temps
       
     //    TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
       
       
         MoneyField::new('total')->setCurrency('EUR'),     // voir entity Order pour la création getTotal
         TextField::new('carrierName', 'Transporteur'),
         MoneyField::new('carrierPrice', 'Frais de transport')->setCurrency('EUR'),


         

         BooleanField::new('isPaid', 'Payé'),
         ArrayField::new('orderDetails', 'produits achetés')->hideOnIndex()




        ];
    }
    
}
