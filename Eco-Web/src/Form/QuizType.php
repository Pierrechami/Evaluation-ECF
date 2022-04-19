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


class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question1', TextType::class, [
                'label' => 'Question n°1'
            ])
            ->add('response1', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrai' => 1,
                    'faux' =>0,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('not_good1', null, [
                'label' => 'Information complémentaire si besoin :'
            ])
            ->add('question2', TextType::class, [
                'label' => 'Question n°2'
            ])
            ->add('response2', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrai' => 1,
                    'faux' =>0,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('not_good2', null, [
                'label' => 'Information complémentaire si besoin :'
            ])
            ->add('question3', TextType::class, [
                'label' => 'Question n°3'
            ])
            ->add('response3', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrai' => 1,
                    'faux' =>0,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('not_good3', null, [
                'label' => 'Information complémentaire si besoin :'
            ])
         #   ->add('section')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
