<?php

namespace App\Form;

use App\Entity\Ami;
use App\Entity\Regime;
use App\Entity\Aliment;
use App\Entity\Allergene;
use App\Entity\SousGroupeAli;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class AmiType extends AbstractType
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
            ->add('regimes', EntityType::class, [
                'class' => Regime::class,
                'label' => 'Régimes alimentaires ',
                'choice_label' => 'regime',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.regime', 'ASC');
                },
                'attr' => [
                    'class' => 'selectRegime ',
                ],
            ])
            ->add('allergies', EntityType::class, [
                'class' => Allergene::class,
                'label' => 'Allergies à un groupe d\'aliments ',
                'choice_label' => 'allergene',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.allergene', 'ASC');
                },
                'attr' => [
                    'class' => 'selectAllergene ',
                ],
            ])
            ->add('allergiesAliment', EntityType::class, [
                'class' => Aliment::class,
                'label' => 'Allergies à un aliment ',
                'choice_label' => 'aliment',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.aliment', 'ASC');
                },
                'attr' => [
                    'class' => 'selectIngredient ',
                ],
            ])
            ->add('degout', EntityType::class, [
                'class' => Aliment::class,
                'label' => 'N\'aime pas un aliment ',
                'choice_label' => 'aliment',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.aliment', 'ASC');
                },
                'attr' => [
                    'class' => 'selectIngredient ',
                ],
            ])
            ->add('degoutGroupeAli', EntityType::class, [
                'class' => SousGroupeAli::class,
                'label' => 'N\'aime pas un groupe d\'aliments  ',
                'choice_label' => 'sousGroupe',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.sousGroupe', 'ASC');
                },
                'attr' => [
                    'class' => 'selectSousGroupe ',
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
