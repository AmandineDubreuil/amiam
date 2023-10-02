<?php

namespace App\Form;

use App\Entity\Regime;
use App\Entity\Saison;
use App\Entity\Aliment;
use App\Entity\Allergene;
use App\Entity\SousGroupeAli;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AlimentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('aliment', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control rounded-1 nom',
                ],
            ])
           // ->add('createdAt')
           // ->add('isVerified')
            ->add('allergene', EntityType::class, [
                'class' => Allergene::class,
                'label' => 'Allergène ',
                'choice_label' => 'allergene',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.allergene', 'ASC');
                },
                'attr' => [
                    'class' => 'selectAllergene',
                ],
            ])
            ->add('regime', EntityType::class, [
                'class' => Regime::class,
                'label' => 'Ne convient pas au(x) régime(s) : ',
                'choice_label' => 'regime',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.regime', 'ASC');
                },
                'attr' => [
                    'class' => 'selectRegime',
                ],
            ])
            ->add('sousGroupe', EntityType::class, [
                'class' => SousGroupeAli::class,
                'label' => 'Groupe Alimentaire : ',
                'choice_label' => 'sousGroupe',
                'multiple' => false,
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.sousGroupe', 'ASC');
                },
                'attr' => [
                    'class' => 'selectSousGroupe',
                ],
            ])
            ->add('saison', EntityType::class, [
                'class' => Saison::class,
                'label' => 'Naturellement présent en : ',
                'choice_label' => 'moisL',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.moisC', 'ASC');
                },
                'attr' => [
                    'class' => 'selectMoisL',
                ],
            ])
           // ->add('degoutAmis')
           // ->add('allergieAmis')
          //  ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aliment::class,
        ]);
    }
}
