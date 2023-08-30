<?php

namespace App\Form;

use App\Entity\UserFamily;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserFamilyType extends AbstractType
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
                //'placeholder' => 'Select a value',
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control rounded-1',
                ],
            ])
            //    ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserFamily::class,
        ]);
    }
}
