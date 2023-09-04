<?php

namespace App\Controller\Admin;

use App\Entity\Aliment;
use Doctrine\ORM\Mapping\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AlimentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Aliment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Aliments')
            ->setEntityLabelInSingular('Aliment')
            ->setPageTitle('index', 'Amiam - Administration des aliments
            ');
    }
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm()
            ->setFormTypeOption('disabled', 'disabled');
        yield   TextField::new('aliment');
        yield AssociationField::new('sousGroupe');
        yield AssociationField::new('regime')
        ->setLabel('Ne convient pas pour les régimes');
        yield AssociationField::new('allergene');
        yield AssociationField::new('saison');
    }
}