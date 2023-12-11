<?php

namespace App\Form;

use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('truc',HiddenType::class,[
                'mapped'=>false,
                ])
            ->add('dateFac',BirthdayType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Date Facture',
                'required' => true,
                'attr' => ['required'=>true,
                // 'readonly'=>true,
                'class'=>'form-control mb-2 form-control-solid', 
                'placeholder' => 'Date de la Facture'],
            ])
            ->add('montantFac', NumberType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Montant Total',
                'required' => false,
                'attr' => [
                'required'=>false, 
                'class'=>'decimal form-control mb-2 form-control-solid', 
                'placeholder' => 'Montant Total'],
            ])
            ->add('elementFactures', CollectionType::class, [
                'required' => true,
                'entry_type' => ElementFactureType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
