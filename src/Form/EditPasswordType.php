<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'constraints' => new NotBlank(),
            'invalid_message' => 'Les mots de passes doivent correspondrent.',
            'first_options'  => array('label' => 'Mot de passe *'),
            'second_options' => array('label' => 'Répéter le mot de passe *'),
        ))
        ->add('submit', SubmitType::class, [
            'label' => 'Modifier le mot de passe ',
            'attr'=>['class'=>'btn btn-danger'],
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
