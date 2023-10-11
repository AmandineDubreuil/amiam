<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\RecetteCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class RecetteNewBeginType extends AbstractType
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

            ->add('prive', HiddenType::class, [
                'label' => 'Recette privée ',
                'required' => false,
                'data' => true,
                'attr' => [
                    'class' => 'form-optional',
                ],
            ])
            ->add('categorie', EntityType::class, [
                'class' => RecetteCategorie::class,
                'label' => 'Catégorie de recettes *',
                'choice_label' => 'categorie',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'data' => $options['data']->getCategorie(), // Pass the values from the database
                'attr' => [
                    'class' => 'categorie',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
