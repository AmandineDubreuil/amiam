<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\RecetteCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => true,
            ])
            ->add('nbPersonnes', NumberType::class, [
                'required' => true,
            ])
            ->add('tpsPreparation', NumberType::class, [
                'required' => false,
            ])
            ->add('tpsCuisson', NumberType::class, [
                'required' => false,
            ])
            ->add('tpsRepos', NumberType::class, [
                'required' => false,
            ])
            ->add('description') //, CKEditorType::class, ['label' => 'Description :']
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
            ->add('video')
            ->add('prive', CheckboxType::class, [
                'label' => 'Recette privée ',
                'required' => false,
              //  'data' => true,
                // 'label_html' => true,
                // 'constraints' => [
                //     new IsTrue(),
                // ],

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
