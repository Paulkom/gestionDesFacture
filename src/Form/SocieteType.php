<?php

namespace App\Form;

use App\Entity\Societe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use App\Form\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Dénomination',
                'required' => true,
                'attr' => ['required'=>true,
                'class'=>'form-control mb-2 unicite',
                'data-column' =>'Societe|nom',  
                'placeholder' => 'Entrer la denomination'],
            ])
            ->add('sigle', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Sigle',
                'required' => true,
                'attr' => ['required'=>true,
                'data-column' =>'Societe|sigle', 
                'class'=>'form-control mb-2 unicite', 
                'placeholder' => 'Entrer le sigle'],
            ])
            ->add('raisonSocial', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Raison sociale',
                'required' => true,
                'attr' => ['required'=>true,
                'class'=>'form-control mb-2 unicite', 
                'data-column' =>'Societe|raisonSocial', 
                'placeholder' => 'Entrer la raison sociale'],
            ])
            ->add('apiLink', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Lien API',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Lien API'],
            ])
            ->add('ifu', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'N° IFU',
                'required' => true,
                'attr' => ['required'=>true,
                'class'=>'form-control mb-2 ifu unicite', 
                'data-column' =>'Societe|ifu',
                // 'data-fv-numeric'=>"true",
                // 'data-fv-numeric___message'=>"La valeur n'est pas un nombre",
                'data-fv-string-length'=>"true",
                'data-fv-string-length___min'=>"13",
                'data-fv-string-length___max'=>"13",
                'data-fv-string-length___message'=>"L'IFU doit comporter 13 chiffres",
                'placeholder' => 'Entrer le numero IFU (13 chiffres)'],
            ])
            ->add('rccm', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'N° RCCM',
                'required' => true,
                'attr' => ['required'=>true,
                'data-column' =>'Societe|rccm',
                'class'=>'form-control mb-2 unicite', 
                'placeholder' => 'Entrer le numero du Registre de commerce'],
            ])
            ->add('adresse', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Adresse',
                'required' => true,
                'attr' => ['required'=>true,
                'data-column' =>'Societe|adresse',
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer l\'adresse'],
            ])
            ->add('keys', HiddenType::class,[
                'mapped' => false,
                'attr' => ['class'=>'keys','data-id'=>'','data-column' =>'Societe|raisonSocial|ifu|rccm|nom|adresse|statut',]
            ])
            ->add('logo',MediaType::class, ['required' => false])
            ->add('entete',MediaType::class, ['required' => true])
            ->add('piedDePage',MediaType::class, ['required' => false])
            ->add('apiNim', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'API Nim',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'API Nim'],
            ])
            ->add('apiToken', TextareaType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'API Token',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'API Token'],
            ])
            ->add('estAdmin', CheckboxType::class, [
                'label_attr' => ['class' => 'fs-6 fw-bold form-label mt-3'],
                'label' => 'Est Admin ?',
                'label_html' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            
            ->add('estModeDeConnexion', CheckboxType::class, [
                // 'label_attr' => ['class' => 'form-label'],
                'label_attr' => ['class' => 'fs-6 fw-bold form-label mt-3'],
                'label' => 'Con OPT ?',
                'label_html' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('messageCommercial', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Message Commercial',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Message'],
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
            $data = $event->getData();
            $form = $event->getForm();
            if(!(null === $data))
            {
                $form->remove('keys')
                ->add('keys', HiddenType::class, [
                    'mapped'=>false,
                    'attr' => [
                        'class'=>'keys',
                        'data-id'=>$data->getId(),
                        'data-column' =>'Societe|raisonSocial|ifu|rccm|nom|adresse|statut'
                    ]
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Societe::class,
        ]);
    }
}
