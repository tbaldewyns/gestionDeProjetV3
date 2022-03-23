<?php

namespace App\Form;

use App\Entity\DataType;
use App\Repository\DataTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DataSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EntityType::class , [
                'class' => DataType::class,
                'query_builder' => function (DataTypeRepository $repo) {
                    return $repo->createQueryBuilder('d')
                    ->orderBy('d.value', 'ASC');;
                },
                'required' => false,
                'label' => false,
                'placeholder' => "Type",
            ])
            ->add('local', ChoiceType::class, [
                "choices" => [
                    "1303" => "1303",
                    "2503" => "2503"
                ],
                'required' => false,
                'label' => false,
                'placeholder' => "Local",
            ])
            ->add('frequence', ChoiceType::class, [
                "choices" => [
                    "Week" => "Week", 
                    "Month" => "Month",
                    "Trimsestre" => "Trimsestre",
                    'Year' => 'Year'
                  ],
                  'required' => false,
                  'label' => false,
                  'placeholder' => "Fréquence",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}