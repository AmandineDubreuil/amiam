<?php

namespace App\Controller\Admin;

use App\Entity\Allergene;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AllergeneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Allergene::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Allergies alimentaires')
            ->setEntityLabelInSingular('Allergie alimentaire')
            ->setPageTitle('index', 'Amiam - Administration des Allergies alimentaires');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('allergene'),
           
        ];
    }
}
