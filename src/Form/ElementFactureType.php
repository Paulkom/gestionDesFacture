<?php

namespace App\Form;

use App\Entity\ElementFacture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElementFactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Désignation',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Initialisation du projet'],
            ])
            ->add('qte', NumberType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Quantité',
                'required' => false,
                'attr' => ['required'=>false, 
                'readonly'=>false,
                'class'=>'form-control form-control-sm decimal', 
                'placeholder' => '1'],
            ])
            ->add('mntTotal', NumberType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Montant total',
                'required' => false,
                'attr' => ['required'=>false, 
                'readonly'=>false,
                'class'=>'form-control form-control-sm decimal', 
                'placeholder' => '2000'],
            ])
            ->add('valeur', NumberType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Valeur',
                'required' => false,
                'attr' => ['required'=>false, 
                'readonly'=>false,
                'class'=>'form-control form-control-sm decimal', 
                'placeholder' => '2000'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ElementFacture::class,
        ]);
    }
}
