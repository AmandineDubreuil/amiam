<?php

namespace App\Form;

use App\Entity\Ami;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class AmiNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control rounded-1',
                ],
            ])
            ->add('dateNaissance', BirthdayType::class, [
                'required' => true,
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control rounded-1',
                ],
            ])
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
            'data_class' => Ami::class,
        ]);
    }
}
