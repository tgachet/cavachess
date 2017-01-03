<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetitionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name',
                        TextType::class,
                        [
                            'label' => 'Nom de la compétition'
                        ])
                ->add('type_of_game_id',
                        EntityType::class,
                        [
                            'label' => 'Type de la compétition',
                            'class' => 'AppBundle:TypeOfGame',
                            'choice_label' => 'name',
                            'placeholder' => 'Choisissez un type de jeu',
                            'attr' => array('class' => 'test'),
                        ])
                ->add('game_mode_id',
                        EntityType::class,
                        [
                            'label' => 'Mode de jeu',
                            'class' => 'AppBundle:GameMode',
                            'choice_label' => 'name',
                            'placeholder' => 'Choisissez un mode de jeu',
                        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Competition'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_competition';
    }


}
