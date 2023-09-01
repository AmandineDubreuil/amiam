<?php

namespace App\Controller\Admin;

use App\Entity\GroupeAli;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GroupeAliCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GroupeAli::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Groupes Aliments')
            ->setEntityLabelInSingular('Groupe Aliments')
            ->setPageTitle('index', 'Amiam - Administration des Groupes d\'aliments
            ');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('groupe'),


        ];
    }
}
