<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, ["label" => "Nom"])
            ->add('firstName', TextType::class, ["label" => "Prénom"])
            ->add('birthDate', DateType::class,[
                "label" => "Date de Naissance",
                "years"=>range(date('Y')-100, date('Y')-18),
                "format" => 'dd MM yyyy'
            ])
            ->add('email', TextType::class, ["label" => "Adresse e-mail"])
            /*->add('password', RepeatedType::class,[
                "type"=>PasswordType::class,
                "first_options" => ["label" => "Mot de passe"],
                "second_options" => ["label" => "Confirmer le mot de passe"]
            ])*/
            ->add("submit", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
