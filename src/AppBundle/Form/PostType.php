<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
            TextType::class,
            [
                'label' => 'Titre de du post',
            ])
            ->add('content',
            TextareaType::class,  
            [
                'label' => 'Post',
                'required' => false, // On enlève required parce que ça pose pb avec la surcouche
                'attr' => array('rows' => 10, 'class' => 'wysiwyg'),
            ])
            ->add('description',
            TextareaType::class,        
            [
                'label' => 'Description',
            ])
//            ->add('author')
            ->add('categories',
            EntityType::class, // input type Entity (select, radio, checkbox)
            [
                'label' => 'Catégories',
                'class' => 'AppBundle:Category', // Nom de l'entité
                'choice_label' => 'name', // Le champ de l'entité qui va s'afficher dans les options
                'multiple' => true,
                'expanded' => true,
//                'placeholder' => 'Choisissez une catégorie' // Pour avoir une première option vide
            ])
            ->add('picture',
            FileType::class, // <input type="file">
            [
                'label' => 'Image',
                'required' => false, // pour rendre le champ optionnel
                'data_class' => null,
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }

}
