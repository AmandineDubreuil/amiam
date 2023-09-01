<?php

namespace App\Controller\Admin;

use App\Entity\Allergene;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AllergeneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Allergene::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
