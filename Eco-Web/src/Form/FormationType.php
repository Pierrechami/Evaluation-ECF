<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title' , null , [
                'label' => 'Titre de la formation '
            ])
            ->add('teaser_text' , TextareaType::class, [
                'label' => 'Description (250 caractères maximum)',
                'constraints' => [
                    new Length([
                        'max' => 250,
                        'maxMessage' => 'La description de votre formation ne doit pas dépasser  {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                    ]),
                ],
            ])
            #->add('user')
            ->add('picture', FileType::class, [
                // unmapped means that this field is not associated to any entity property
                'label'=> 'Image de la formation' ,
                // a voir aprés si je le laisse en
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
