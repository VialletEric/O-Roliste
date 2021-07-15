<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=>"e-mail *",
                'constraints' => new NotBlank(),
            ])
            ->add('username', TextType::class,[
                'label'=>"Pseudo *",
                'constraints' => new NotBlank(),
            ])
            ->add('password',PasswordType::class,[
                'label'=>'Mot de passe, minimum 8 caractères. *',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['min'=>8]),
                ]
            ])
            ->add('avatar',FileType::class, [
                'label'=>'Avatar',
                'required' => false,
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
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
