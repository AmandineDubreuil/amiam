<?php

namespace App\Controller\Admin;

use App\Entity\SousGroupeAli;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SousGroupeAliCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SousGroupeAli::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Sous-groupes Aliments')
            ->setEntityLabelInSingular('Sous-groupe Aliments')
            ->setPageTitle('index', 'Amiam - Administration des Sous-groupes d\'aliments
            ');
    }
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm()
            ->setFormTypeOption('disabled', 'disabled');
        yield   TextField::new('sousGroupe');
        yield AssociationField::new('groupe');

    }
}
