<?php

namespace App\Form\Type;


use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, ['label' => 'Data wydarzenia'])
            ->add('city', TextType::class, ['label' => 'Miasto'])
            ->add('source', TextType::class, [
                'label' => 'Link do wydarzenia', 
                'help' => 'Aby widget zadziałał, należy podać url  grupy na facebooku. NALEŻY PODAĆ URL GRUPY, NIE WYDARZENIA (np. https://www.facebook.com/wildanimalexpo)',
                'required' => false,
            ])
            ->add('create', SubmitType::class, ['label' => 'Zapisz']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }

}