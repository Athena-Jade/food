<?php

namespace App\Controller\Admin;


use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return[
            TextField::new('name'),

            SlugField::new('slug')->setTargetFieldName('name'),

            ImageField::new('illustration')
                ->setUploadDir("public/upload")
                ->setBasePath('upload/')   
             //    ->setUploadedFileNamePattern('[randomhash].[extension]') // je n'ai pas besoin de ça
                ->setRequired('false'),     # ne fonctionne pas, je suis obligée de mettre 2 fois la photo en cas de modification, bug Symfony
            
            TextField::new('subtitle'),
            
            TextareaField::new('description'),

            MoneyField::new('price')
               ->setCurrency('EUR'),

            AssociationField::new('category'),
    
        ];

    }






















}
