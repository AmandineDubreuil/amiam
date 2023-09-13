<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                
        ->add('avatar', FileType::class, [
            'label' => 'avatar (fichier image) ',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '10000k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Merci de télécharger une image valide.',
                ])
            ]
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
