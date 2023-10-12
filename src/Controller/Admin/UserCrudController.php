<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
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

    public function deleteEntity(
        EntityManagerInterface $entityManager,
        $entityInstance
    ): void {
        if (!$entityInstance instanceof User) return;
        $oldImage = $entityInstance->getAvatar();  
              

        if ($oldImage) {
            $folder = 'avatars';
            $path = $this->params->get('images_directory') . $folder;
            // suppression de la miniature
            $mini = $path . '/mini/' . $oldImage;
//dd($mini);
            if (file_exists($mini)) {
                unlink($mini);
             
            }
            parent::deleteEntity($entityManager, $entityInstance);

            
        }
    }
}
