<?php

namespace App\Controller\Admin;

use App\Entity\Regime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RegimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Regime::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Régimes alimentaires')
            ->setEntityLabelInSingular('Régime alimentaire')
            ->setPageTitle('index', 'Amiam - Administration des Régimes alimentaires');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('regime'),
           
        ];
    }
    
}
