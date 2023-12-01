<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
// use App\Form\FonctionUtilisateurType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
{
    private $em;
    private $profilRepo;

    public function __construct(EntityManagerInterface $entityManager, ProfilRepository $profilRepo)
    {
        $this->em = $entityManager;
        $this->profilRepo = $profilRepo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $routes = array();
        $profils = $this->profilRepo->findBy(['deletedAt' => NULL]);
       
        foreach ($profils as $pro ) {
            if($pro->getRoles() != []){
                $routes[$pro->getTitre()] = $pro->getId();
            }
        }

        $builder
            ->add('nom', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Nom',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le nom de la personne'],
            ])
            ->add('username', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Pseudo',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le nom de la personne'],
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
            ->add('profil', ChoiceType::class,[
                'choices' => $routes,
                'label' => 'Profil(s)',
                // 'mapped' => false,
                'multiple' => true,
                'placeholder' => 'Sélectionner le(s) profil(s)',
                'attr' => [ 
                    'required' => false,
                    'data-control' => 'select2',
                    'class' => 'js-example-basic-single form-control' 
                ],
                'required' => false,
            ])
            ->add('profession', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Profession',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le profession de la personne'],
            ])
            ->add('prenom', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Prénoms',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le(s) prénom(s) de la personne'],
            ])
            ->add('tel', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Téléphone',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2 telephone unicite', 
                'data-column' =>'Utilisateur|tel',
                'placeholder' => 'Entrer le téléphone'],
            ])
            ->add('email', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Email',
                'required' => false,
                'attr' => ['required'=>false, 
                'class'=>'form-control mb-2 unicite',
                'data-column' =>'Utilisateur|email',
                'data-fv-email-address'=>"true",
                'data-fv-email-address___message' => "Cet email n'est pas valide.",
                'placeholder' => 'Entrer l\'email de la personne'],
            ])
            ->add('adresse', TextareaType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Adresse',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer l\'adresse de la personne'],
            ])
            ->add('dateNais', BirthdayType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Date de naissance',
                'required' => false,
                'attr' => ['required'=>false,
                'class'=>'form-control mb-2', 
                'placeholder' => 'Date de naissance'],
            ])
            ->add('sexe', ChoiceType::class, [
                'label_attr' => ['class' => 'form-label'],
                'choices'  => [
                    'Masculin' => 'Masculin',
                    'Feminin' => 'Feminin',
                ],
                'label' => 'Sexe',
                'required' => true,
                'placeholder' => 'Sélectionnez le sexe...',
                'attr' => ['required'=>true,
                'class'=>'form-control mb-2', 
                'data-control' => "select2",
                'placeholder' => 'Sexe'],
            ])
            ->add('password', RepeatedType::class, [
                'label_attr' => ['class' => 'form-label'],
                'type' => PasswordType::class,
                'mapped'=>false,
                'invalid_message' => 'Ce mot de passe ne correspond pas.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'mapped' => false,
                'first_options'  => ['label' => 'Mot de passe', 'attr'=>['placeholder' => 'Mot de passe', 'class' => 'form-control mt-2']],
                'second_options' => ['label' => 'Confirmation', 'attr'=>['placeholder' => 'Confirmer le mot de passe', 'class' => 'form-control mt-2']],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('civilite', ChoiceType::class, [
                'label_attr' => ['class' => 'form-label'],
                'choices'  => [
                    'Monsieur' => 'Monsieur',
                    'Madame' => 'Madame',
                    'Mademoiselle' => 'Mademoiselle',
                    'Docteur' => 'Docteur',
                ],
                'label' => 'Civilité',
                'required' => true,
                'placeholder' => 'Sélectionnez la civilité...',
                'attr' => ['required'=>true,
                'class'=>'form-control mb-2', 
                'data-control' => "select2",
                'placeholder' => 'Civilite'],
            ])
            // ->add('fonctionUtilisateurs', CollectionType::class, [
            //     'required' => false,
            //     'entry_type' => FonctionUtilisateurType::class,
            //     'entry_options' => ['label' => false],
            //     'label' => false,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            //     'prototype_name' => '__fonctionUtilisateurs__'
            // ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
