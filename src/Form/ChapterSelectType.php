<?php

namespace App\Form;

use App\Entity\Story;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChapterSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('isValid',      ChoiceType::class, array(
                'choices'     => array(
                    'Chapitres publiés validés' => true,
                    'Chapitres publiés non validés' => false
                ),
                'expanded'    => true,
                'multiple'    => false,
                'label'     => false

            ))
            ->add('story',       EntityType::class, array(
                'class'           => Story::class,
                'choice_label'    => 'title',
                'multiple'        => false,
                'expanded'        => false,
                'label' => false
            ))
            ->add('submit',     SubmitType::class, array(
                'label'     => 'Envoyer'));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(['validation_groups' => ['no-validation']]);
    }

}
