<?php

namespace App\Form;

use App\Entity\GameMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GameMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body',TextareaType::class,[
                'constraints'=>new NotBlank(['message'=>'Veuillez completer ce champ']),
                'label'=>"Message *"
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr'=>['class'=>'btn btn-danger btn-sm'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameMessage::class,
        ]);
    }
}
