<?php

namespace App\Form;

use App\Entity\Quiz;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class QuizApprenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('response1', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrais' => 1,
                    'faux' =>0,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('response2', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrais' => 1,
                    'faux' =>0,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('response3', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrais' => 1,
                    'faux' => 0,
                ],
                'expanded' => true,
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
