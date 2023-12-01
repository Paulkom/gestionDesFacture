<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('file', FileType::class, [
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Joindre une image',
            'required' => true,
            'empty_data' => '',
            'attr' => [
                'class' => 'form-control d-none',
                'placeholder' => 'Pièce à joindre',
                'accept'=>'image/jpg,image/png,image/jpeg,image/gif',
            ]
        ])
        ->add('nomMedia', TextType::class, [
            'label' => false,
            'required' => true,
            'attr' => [
                'required'=>false,
                'class'=>'form-control',
                'aria-label'=>'Nom du média',
                'aria-describedby'=>'basic-addon2',
                'placeholder' => 'Nom du média',
                'readonly' => true,
            ],
        ])
        ->add('numMedia', TextType::class, [
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Référence de la pièce',
            'required' => false,
            'empty_data' => '',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'N° de la pièce',
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
