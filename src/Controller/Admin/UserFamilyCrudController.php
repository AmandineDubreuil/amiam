<?php

namespace App\Controller\Admin;

use App\Entity\UserFamily;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserFamilyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserFamily::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Membres de la famille d\'un utilisateur')
            ->setEntityLabelInSingular('Membre de la famille d\'un utilisateur')
            ->setPageTitle('index', 'Amiam - Administration des membres de la famille d\'un utilisateur');
    }
    public function configureFields(string $pageName): iterable
    {

        yield IdField::new('id')
            ->hideOnForm()
            ->setFormTypeOption('disabled', 'disabled');
        yield   TextField::new('prenom');
        yield DateField::new('date_naissance');
        yield AssociationField::new('user');
    }
}
