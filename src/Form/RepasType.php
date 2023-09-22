<?php

namespace App\Form;

use App\Entity\Repas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RepasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, array(
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd', 
                'attr' => [
                    'class' => 'form-control rounded-1',
                ],
            ))
            ->add('commentaire', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control rounded-1 commentaire',
                ],
            ])
            // ->add('amis')
            // ->add('recettes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repas::class,
        ]);
    }
}
