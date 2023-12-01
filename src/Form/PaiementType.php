<?php

namespace App\Form;

use App\Entity\factures;
use App\Entity\Facture;
use App\Entity\ModePaiement;
use App\Entity\Paiement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em  = $em; 
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('truc',HiddenType::class,[
            'mapped'=>false,
            ])
        ->add('datePaie',BirthdayType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Date de paiement',
            'required' => true,
            'attr' => ['required'=>true,
            'readonly'=>true,
            'class'=>'form-control mb-2 form-control-solid', 
            'placeholder' => 'Date de paiement'],
        ])
        ->add('montantPaie',NumberType::class, [
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Montant payé',
            'required' => true,
            'attr' => ['required'=>true, 
            //'readonly'=>true,
            'class'=>'form-control mb-2', 
            'placeholder' => 'montant payé'],
        ])
        ->add('restAPayer',NumberType::class, [
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Montant reste à payer',
            'required' => false,
            'attr' => ['required'=>false, 
            'readonly'=>true,
            'class'=>'form-control mb-2 form-control-solid', 
            'placeholder' => 'Montant reste à payer'],
        ])
        ->add('montantFact',NumberType::class, [
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Montant Facture',
            'mapped'=>false,
            'required' => false,
            'attr' => ['required'=>false, 
            'readonly'=>true,
            'class'=>'form-control mb-2 form-control-solid', 
            'placeholder' => 'Montant de la facture'],
        ])
        
        ->add('facture',EntityType::class, [
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Facture',
            'class' => Facture::class,
            'query_builder' => function(){
                //dd($socie);
                $qb = $this->em->createQueryBuilder()
                        ->select('c')
                        ->from('App\\Entity\\Facture', 'c');
                        $qb->andwhere("c.montantRest != 0 OR c.montantRest IS NULL")->orderBy('c.refFact','DESC')
                        
                ;
                //dd($qb->getDQL());
                return $qb;
            },
            'choice_attr' => function(Facture $facture){
                $montantRest=0.00;
                $montantRest = ($facture->getMontantRest() == "") ? $facture->getMontantFac() : $facture->getMontantRest() ;
                return [
                    'data-montantIni'  => $facture->getMontantFac(),
                    'data-montantRest' => $montantRest,
                ];
            },
            'placeholder' => 'Sélectionnez une facture...',
            'attr' => [
                'required' => true,
                'data-control' => "select2",
                'class' => 'form-select'
            ],
        ])
        ->add('modePaiement',EntityType::class, [
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Mode de paiement',
            'class' => ModePaiement::class,
            'query_builder' => function(){
                $qb = $this->em->createQueryBuilder()
                    ->select('m')
                    ->from('App\\Entity\\ModePaiement', 'm')
                    ->where('m.deletedAt IS NULL')
                ;
                return $qb;
            },
            'placeholder' => 'Sélectionnez le mode de paiement...',
            'attr' => [
                'required' => true,
                'data-control' => "select2",
                'class' => 'form-select'
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
