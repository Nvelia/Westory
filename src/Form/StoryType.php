<?php

namespace App\Form;

use App\Entity\Story;
use App\Form\ChapterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class StoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          TextType::class, array(
                'label' => 'Titre',
                'attr'  => array(
                    'class' => 'form-control'
                )
            ))
            ->add('chapterLimit',   IntegerType::class, array(
                'label' =>  'Nombre de chapitres',
                'attr'  =>  array(
                    'min'   => 10,
                    'max'   => 50,
                    'class' => 'form-control',
                    'placeholder'   => '10'
                )
            ))
            ->add('chapters', ChapterType::class, array(
                'mapped'    => false,
                'label'     => 'Introduction',
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
            'data_class' => Story::class,
        ]);
    }
}
