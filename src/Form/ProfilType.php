<?php

namespace App\Form;

use App\Entity\Profil;
use App\Repository\MenuRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    protected $menuRepo;
    public function __construct(MenuRepository $repo)
    {
        $this->menuRepo = $repo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $routes = array();
        $menus = $this->menuRepo->findBy(['deletedAt' => NULL, 'sousTitre' => NULL]);
       
        foreach ($menus as $menu ) {
            if($menu->getRoles() != []){
                $routes[$menu->getTitre()] = $menu->getRoles();
            }
        }

        $builder
        ->add('titre', TextType::class, [
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Désignation',
            'required' => false,
            'attr' => [
                'class'=>'form-control',
                'placeholder' => 'Désignation profil'
            ],
        ])
        ->add('roles', ChoiceType::class,[
            'choices' => $routes,
            'label' => 'Route',
            'multiple' => true,
            'placeholder' => 'Sélectionner une route',
            'attr' => [ 
                'data-control' => 'select2',
                'class' => 'js-example-basic-single form-control' 
            ],
            'required' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
        ]);
    }
}
