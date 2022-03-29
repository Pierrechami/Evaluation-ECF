<?php

namespace App\Form;

use App\Entity\Lesson;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title' , TextType::class, [
                'label' => 'Titre de la leçon'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Rédigez le contenu du cours'
            ])
            ->add('video', TextType::class, [
                'label' => 'Inclure le lien d\'une vidéo',
                'attr' => ['placeholder' => 'https://www.youtube.com/embed/4t3fNkGwRWo'],
            ])
            ->add('picture1', FileType::class, [
                'label' => 'Photo 1 (facultatif)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (Extension .jpeg/.jpg/.png)',
                    ])
                ],

            ])
            ->add('picture2', FileType::class, [
                'label' => 'Photo 2 (facultatif)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (Extension .jpeg/.jpg/.png)',
                    ])
                ],

            ])
            ->add('picture3', FileType::class, [
                'label' => 'Photo 3 (facultatif)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (Extension .jpeg/.jpg/.png)',
                    ])
                ],

            ])

           # ->add('section')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
        ]);
    }
}
