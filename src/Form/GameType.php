<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Editor;
use App\Entity\Platform;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('platforms', EntityType::class, [
                "label" => 'Plaformes de jeux',
                "class" => Platform::class,
                "choice_label" => function(Platform $platform){
                    return $platform->getName();
                },
                "expanded"  => true,
                "multiple"  => true,
            ])
            ->add('description', TextareaType::class)
            ->add('releaseDate', DateType::class,[
                "label" => "Date de sortie",
                "years"=>range(date('Y')-40, date('Y')+50),
                "format" => 'dd MM yyyy'
            ])
            ->add('editor', EntityType::class, [
                'class' => Editor::class,
                'choice_label' => function (Editor $editor) {
                    return $editor->getSociety();
                }
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
