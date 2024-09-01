<?php

namespace App\Form;

use App\Entity\Tournament;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название турнира',
            ])
            ->add('teams', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'label' => 'Команды',
                'multiple' => true,
                'expanded' => true, //--> Для отображения в виде чекбоксов
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
