<?php

namespace App\Controller\Admin;

use App\Entity\Aliment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AlimentCrudController extends AbstractCrudController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


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
        yield AssociationField::new('user')
            ->hideOnForm();
        yield DateTimeField::new('createdAt')
            // ->hideOnForm()
            ->setFormTypeOption('disabled', 'disabled');
        yield BooleanField::new('is_verified');
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->security->getUser();
        if (!$entityInstance instanceof Aliment) return;
        $entityInstance->setCreatedAt(new \DateTimeImmutable);
        $entityInstance->setUser($user);
     //   dd($entityInstance);
     parent::persistEntity($entityManager, $entityInstance);
    }
}
