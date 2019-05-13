<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ForgottenPassType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',       TextType::class, array(
                'label' => 'Identifiant',
                'attr'  => array(
                    'class' => 'form-control'
                )
            ))
            ->add('email',   EmailType::class, array(
                'label' => 'Adresse Email',
                'attr'  => array(
                    'class' => 'form-control'
                )
            ))
            ->add('submit',         SubmitType::class, array(
                'label' => 'Envoyer',
                'attr'  => array('class' => 'submit'),
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
