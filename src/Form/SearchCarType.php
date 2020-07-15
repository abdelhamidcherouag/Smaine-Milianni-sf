<?php

namespace App\Form;

use App\Entity\City;
use App\Faker\CarProvider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCarType extends AbstractType
{

    const PRICE = [1000,2000,3000,5000];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model')
            ->add('color',ChoiceType::class,[
                'choices'=>[
                    array_combine(CarProvider::COLOR,CarProvider::COLOR)
                ]
            ])
            ->add('carburent',ChoiceType::class,[
                'choices'=>[
                    array_combine(CarProvider::CARBURANT,CarProvider::CARBURANT)
                ]
            ])
            ->add('city',EntityType::class,[
                'class'=> City::class,
                'choice_label'=> 'name'
            ])

            ->add('minimumPrice',ChoiceType::class,[
                'label' => 'Prix minimun',
                'choices' => array_combine(self::PRICE,self::PRICE)
            ])

            ->add('maximumPrice',ChoiceType::class,[
                'label' => 'Prix maximun',
                'choices' => array_combine(self::PRICE,self::PRICE)
            ])

            ->add('recherche',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
