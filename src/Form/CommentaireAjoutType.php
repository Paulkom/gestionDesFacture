<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Facture;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireAjoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('objet', TextType::class,[
            //     'label_attr' => ['class' => 'form-label'],
            //     'label' => 'Objet',
            //     'required' => true,
            //     'attr' => [
            //     'required'=>true, 
            //     'class'=>'form-control mb-2 unicite', 
            //     'data-column' =>'Commentaire|objet',
            //     'placeholder' => "Entrer l'objet du commentaire"
            // ],
            // ])
            ->add('message',TextareaType::class,[
                'label_attr' => ['class' => 'form-label'],
            'label' => 'Message',
            'required' => true,
            'attr' => [
                'required'=>true, 
                'class'=>'form-control mb-2 unicite', 
                'data-column' =>'Commentaire|message',
                'placeholder' => 'Entrer le message du commentaire'
            ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
