<?php

namespace App\Form\Type;

use App\Entity\Animal;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{

    private $images;
    private $imageRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->imageRepository = $doctrine->getRepository('App\Entity\Image');
        $this->images = $this->imageRepository->findAll();
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('class', ChoiceType::class, [
            'choices' => [
                'Jaszczurka' => 'lizards',
                'Wąż' => 'snakes',
                'Żółw' => 'turtles',
                'Płaz' => 'amphibians',
                'Pajęczak' => 'arachnids',
                'Modliszka' => 'mantises',
                'Inne' => 'others',
            ],
            'label' => 'Kategoria', 
        ])
            ->add('spieces', TextType::class, ['label' => 'Gatunek'])
            ->add('common_name', TextType::class, ['label' => 'Nazwa zwyczajowa'])
            ->add('price', IntegerType::class, ['label' => 'Cena'])
            ->add('in_stock', IntegerType::class, [
                'label' => 'Sztuk na stanie',
                'required' => false,
                ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
                'required' => false,
            ])
            ->add('animal_table', AnimalTableType::class, ['label' => 'Tabela'])
            ->add('create', SubmitType::class, ['label' => 'Zapisz']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }

}