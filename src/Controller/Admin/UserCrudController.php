<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')
            ->setPageTitle('index', 'Amiam - Administration des Utilisateurs');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('pseudo'),
            TextField::new('email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('avatar'),
            ArrayField::new('roles')
                ->hideOnIndex(),

            DateField::new('createdAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('modifiedAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
            BooleanField::new('is_verified'),

            // TextEditorField::new('description'),
        ];
    }
}
