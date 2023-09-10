<?php

namespace App\Form\Type;

use App\Entity\AnimalTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('temperature', TextType::class, ['label' => 'Temperatura'])
            ->add('humidity', TextType::class, ['label' => 'Wilgotność'])
            ->add('warming', TextType::class, ['label' => 'Grzanie'])
            ->add('terrarium_dimensions', TextType::class, ['label' => 'Wymiary terrarium'])
            ->add('substrate', TextType::class, ['label' => 'Podłoże'])
            ->add('food', TextType::class, ['label' => 'Pokarm'])
            ->add('uvb', TextType::class, ['label' => 'UVB'])
            ->add('lifespan', TextType::class, ['label' => 'Długość życia'])
            ->add('cites', TextType::class, ['label' => 'CITES']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnimalTable::class,
        ]);
    }
}