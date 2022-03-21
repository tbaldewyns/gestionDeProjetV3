<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, array('label' => false))
            ->add('lastname', TextType::class, array('label' => false) )
            ->add('email', EmailType::class, array('label' => false))
            ->add('password', PasswordType::class, array('label' => false))
            ->add('confirmPassword', PasswordType::class, array('label' => false))
            /*->add('accountType', ChoiceType::class, [
                "choices" => [
                    "Admin" => "ROLE_ADMIN", 
                    "Teacher" => "ROLE_TEACHER"
                ],  'label' => false
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}