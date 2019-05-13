<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',       TextType::class, array(
                'label'             => 'Identifiant',
                'attr'              => array(
                    'class'         => 'form-control browser-default'
                )
            ))
            ->add('password',       RepeatedType::class, array(
                'type'              => PasswordType::class,
                'invalid_message'   => 'Les mots de passe ne correspondent pas.',
                'first_options'     => array(
                    'label' => 'Mot de passe',
                        'attr'  => array(
                            'class'         => 'form-control browser-default'
                )),
                'second_options'    => array(
                    'label' => 'Vérification du mot de passe',
                        'attr'  => array(
                            'class'         => 'form-control browser-default'
                )),
            ))
            ->add('email',   RepeatedType::class, array(
                'type'              => EmailType::class,
                'invalid_message'   => 'Les adresse e-mails ne correspondent pas.',
                'first_options'     => array(
                    'label'             => 'Adresse e-mail',
                'attr'              => array(
                    'class'         => 'form-control browser-default'
                )),
                'second_options'    => array(
                    'label'             => 'Vérification de l\'adresse e-mail',
                    'attr'              => array(
                    'class'         => 'form-control browser-default'
                ))
            ))
            ->add('imageFile', FileType::class, array(
                'required'  => false,
                'attr'              => array(
                    'class'         => 'form-control' )
            ))
            ->add('submit',         SubmitType::class, array(
                'label' => 'Envoyer',
                'attr'  => array(
                    'class' => 'submit'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
