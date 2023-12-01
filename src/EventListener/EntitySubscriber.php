<?php

namespace App\EventListener;

use App\Database\NativeQueryMySQL;
use App\Entity\Facture;
use App\Entity\Media;
use App\Entity\Paiement;
use App\Entity\Utilisateur;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;

ini_set('xdebug.max_nesting_level', 9999);

class EntitySubscriber implements EventSubscriberInterface
{
    protected $security;
    protected $em;
    protected $native;
    protected $cmp = 0;
    

    private SessionInterface $session;
    private $profilRepo;

    public function __construct(EntityManagerInterface $em, ProfilRepository $profilRepo, Security $security,NativeQueryMySQL $nativ) {
        $this->security = $security;
        $this->em = $em;
        $this->profilRepo = $profilRepo;
        $this->native  = $nativ;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist,
            Events::preRemove,
            Events::postRemove,
            Events::preUpdate,
            Events::postUpdate,
            Events::preFlush,
            Events::postFlush,
            Events::onFlush,
        ];
    }


    public function prePersist(LifecycleEventArgs $args): void
    {
        $user = $this->security->getUser();
        
        $entity = $args->getObject();
        $em = $args->getObjectManager();

        if($entity instanceof Media){
            $entity->preUpload();
            $entity->upload();
        }
        if ($entity instanceof Facture) {
            $year = date("y");
            $prefix = "FA";
            $numcom = $em->getRepository(Facture::class)->countCom();
            $entity->setEstSup(0);
            $entity->setRefFact($prefix . str_pad($numcom + 1, 6, "0", STR_PAD_LEFT).$year);
            $entity->setEmetteur($user->getNom(). " ".$user->getPrenom());
        }

        if ($entity instanceof Paiement) {
            $year = date("y");
            $prefix = "PA";
            $numcom = $em->getRepository(Paiement::class)->countCom();
            $entity->setRefPai($prefix . str_pad($numcom + 1, 6, "0", STR_PAD_LEFT).$year);
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $entity = $args->getObject();


        if($entity instanceof Utilisateur){
            $roles = array();
            if([] != $entity->getProfil()){
                foreach ($entity->getProfil() as $pro) {
                    $profil = $this->profilRepo->find((int)$pro);
                    foreach ($profil->getRoles() as $role) {
                        $roles[] = $role; 
                    }
                }
                $entity->setRoles($roles);
                $this->em->flush();
            }
        }
    
    }



    public function preRemove(LifecycleEventArgs $args)
    {
        $em = $args->getObjectManager();
        $entity = $args->getObject();
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $entity = $args->getObject();
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $args->getObjectManager();

        if($entity instanceof Media){
            $entity->preUpload();
            $entity->upload();
        }
    }


    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $args->getObjectManager();
        $em->flush();
    }

    public function preFlush(PreFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getObjectManager();
    }

    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        
        // $entity = $args->getObject();
        // $em = $args->getObjectManager();
        // if($entity instanceof User){
        //     dump($entity);
        //     if($entity->isEstPersonnel() == 1){
        //         $entity->setUsername($entity->getId());
        //     }
        //     dump($entity);
        // }
        // $em->flush();
        
    }
}
