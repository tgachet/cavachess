<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $new = empty($options['data']->getId());
        
        if (in_array('ROLE_ADMIN', $options['role']) && $options['statut'] != 'logged_user') {
            // do as you want if admin
            $builder
                    ->add(
                            'username',
                            TextType::class,
                            [
                                'label' => 'Pseudo',
                                'disabled' => 'disabled',
                            ]
                    )
                    ->add(
                            'email',
                            EmailType::class,
                            [
                                'label' => 'Email',
                                'disabled' => 'disabled',
                            ]
                    )
                    ->add(
                            'role',
                            ChoiceType::class, array( // crée un select sur une entité
                                'label' => 'Role',
                                'expanded' => true,
                                'multiple' => false,
                                'choices'  => array(
                                            'Admin' => 'ROLE_ADMIN',
                                            'User' => 'ROLE_USER',
                                )    
                            )
                    )
            ;
        }else{
            $passwordOptions = [
                'type' => PasswordType::class, // ... de type password
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
            ];
            
            if (!$new) {
                $passwordOptions['required'] = false;
            }
            
            $builder
                    ->add(
                            'firstname',
                            TextType::class,
                            [
                                'label' => 'Prénom',
                            ]
                    )
                    ->add(
                            'lastname',
                            TextType::class,
                            [
                                'label' => 'Nom',
                            ]
                    )
                    ->add(
                            'username',
                            TextType::class,
                            [
                                'label' => 'Pseudo',
                            ]
                    )
                    ->add(
                            'plainPassword', // le mot de passe en clair, qu'on ne va pas enregistrer en bdd
                            RepeatedType::class, // 2 champs qui doivent être identiques
                            $passwordOptions
                    )
                    ->add(
                            'email',
                            EmailType::class,
                            [
                                'label' => 'Email',
                            ]
                    )
                    ->add(
                            'avatar',
                            FileType::class, // <input type="file">
                            [
                                'label' => 'Avatar',
                                'required' => false, // pour rendre le champ optionnel
                                'data_class' => null,
                            ]
                    )
            ;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => ['create'],
            'role' => ['ROLE_USER'],
            'statut' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
