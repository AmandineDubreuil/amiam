<?php

namespace App\Form;

use App\Entity\Aliment;
use App\Entity\Mesure;
use App\Entity\RecetteIngredient;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RecetteIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite', NumberType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control rounded-1 number',
                ],
            ])
            ->add('aliment', EntityType::class, [
                'class' => Aliment::class,
                'label' => 'Ingrédient ',
                'choice_label' => 'aliment',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.aliment', 'ASC');
                },
                'attr' => [
                    'class' => 'selectIngredient ',
                ],
            ])
            ->add('mesure', EntityType::class, [
                'class' => Mesure::class,
                'label' => 'Unité de mesure ',
                'choice_label' => 'mesure',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.mesure', 'ASC');
                },
                'attr' => [
                    'class' => 'selectMesure ',
                ],
            ])
            //  ->add('recette')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecetteIngredient::class,
        ]);
    }
}
