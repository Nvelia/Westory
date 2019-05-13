<?php

namespace App\Form;

use App\Entity\User;
//use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',    PasswordType::class, array(
                'label'     => 'Mot de passe actuel',
                'mapped'    => false
                ))
            ->add('password',       RepeatedType::class, array(
                'type'              => PasswordType::class,
                'invalid_message'   => 'Les mots de passes saisis doivent correspondre.',
                'first_options'     => array('label' => 'Nouveau Mot de passe'),
                'second_options'    => array('label' => 'Vérification du mot de passe'),
            ))
            ->add('submit',         SubmitType::class, array(
                'label' => 'Envoyer',
                'attr'  => array('class' => 'submitRegistration'),
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }


}
