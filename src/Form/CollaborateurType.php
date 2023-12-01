<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Repository\MenuRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CollaborateurType extends AbstractType
{
    private $em;
    private $profilRepo;

    public function __construct(EntityManagerInterface $entityManager, ProfilRepository $profilRepo)
    {
        $this->em = $entityManager;
        $this->profilRepo = $profilRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {$routes = array();
        $profils = $this->profilRepo->findBy(['deletedAt' => NULL]);
       
        foreach ($profils as $pro ) {
            if($pro->getRoles() != []){
                $routes[$pro->getTitre()] = $pro->getId();
            }
        }

        $builder
            ->add('username')
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('adresse')
            ->add('actif')
            ->add('profil', ChoiceType::class,[
                'label_attr' => ['class' => 'form-label'],
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
                'placeholder' => 'Entrer le username de la personne'],
            ])
            ->add('societe',EntityType::class, [
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'label' => 'Société',
                'class' => Societe::class,
                'placeholder'=> 'Sélectionnez le parent',
                'attr' => [
                    'required'=>true,
                    'class' => 'form-control form-select',
                    'data-control' => "select2",
                    'data-placeholder' => "Sélectionnez le parent "
                ]
            ])
            ->add('password', RepeatedType::class, [
                'label_attr' => ['class' => 'form-label'],
                'type' => PasswordType::class,
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
            ->add('prenom', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Prénoms',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer le(s) prénom(s) de la personne'],
            ])
            ->add('email', EmailType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Email',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer votre mail de la personne'],
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
            ->add('adresse',TextareaType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Adresse',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2', 
                'placeholder' => 'Entrer l\'adresse de la personne'],
            ])
            ->add('tel', TextType::class, [
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Téléphone',
                'required' => true,
                'attr' => ['required'=>true, 
                'class'=>'form-control mb-2 telephone unicite', 
                'data-column' =>'Personnel|tel',
                'placeholder' => 'Entrer le téléphone'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
