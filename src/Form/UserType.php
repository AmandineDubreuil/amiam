<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      //  ->add('email', EmailType::class, [
        //    'required' => true,
     //   ])
        // ->add('pseudo', TextType::class, [
        //     'required' => true,
        // ])
            
        ->add('avatar', FileType::class, [
            'label' => 'avatar (fichier image) ',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '8192k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Merci de télécharger une image valide.',
                ])
            ]
        ])
           // ->add('createdAt')
           // ->add('modifiedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
