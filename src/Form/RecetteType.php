<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\RecetteCategorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control rounded-1 titre',
                ],
            ])
            ->add('nbPersonnes', NumberType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control rounded-1 number',
                                   ],
            ])
            ->add('tpsPreparation', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control rounded-1 number',
                    'placeholder' => 'min',
                ],
            ])
            ->add('tpsCuisson', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control rounded-1 number',
                    'placeholder' => 'min',
                ],
            ])
            ->add('tpsRepos', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control rounded-1 number',
                    'placeholder' => 'min',
                ],
            ])

            ->add('description', HiddenType::class) 
            ->add('photo', FileType::class, [
                'label' => 'photo (fichier image) ',
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
            ->add('video', HiddenType::class)
            ->add('prive', HiddenType::class, [
                'label' => 'Recette privée ',
                'required' => false,

            ])

            ->add('categorie', EntityType::class, [
                'class' => RecetteCategorie::class,
                'label' => 'Catégorie de recettes ',
                'choice_label' => 'categorie',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'data' => $options['data']->getCategorie(), // Pass the values from the database
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
