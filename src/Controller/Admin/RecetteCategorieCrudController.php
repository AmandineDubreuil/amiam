<?php

namespace App\Controller\Admin;

use App\Entity\RecetteCategorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecetteCategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecetteCategorie::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Catégories de recettes')
            ->setEntityLabelInSingular('Catégorie de recettes')
            ->setPageTitle('index', 'Amiam - Administration des Catégories de recettes');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('categorie'),
           
        ];
    }
}
