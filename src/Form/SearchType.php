<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'required' => false,
                'label'=>'Nom',
                'label_attr'=>['class'=>'text-dark'],        
            ])
            ->add('category', null, [
                'expanded' => false,
                'required' => false,
                'label'=>'Catégorie',
                'label_attr'=>['class'=>'text-dark'],
                'placeholder' => 'choisissez',
                'attr' => ['class' => 'form-select'],
                
            ])
            ->add('tags', null, [
                'expanded' => true,
                'multiple' => true, 
                'required' => false,
                'label'=>'Tags',
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input'];
                },
            ])
            ->add('save', SubmitType::class, [
                'label'=>'Recherche',
                'attr' => ['class' => 'btn btn-danger'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
