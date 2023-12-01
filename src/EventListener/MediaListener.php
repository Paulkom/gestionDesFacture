<?php

namespace App\EventListener;

use App\Entity\Media;
use App\Services\FileUploader;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaListener {

    protected $uploader;
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    // -- PERSIST --------------------------------------------------------

    /** @ORM\PrePersist */
    public function prePersistHandler(Media $entity, LifecycleEventArgs $args)
    {
        
        $em = $args->getObjectManager();
        $entity->preUpload();
    }
    /** @ORM\PostPersist */
    public function postPersistHandler(Media $entity, LifecycleEventArgs $args)
    {
        $entity->upload();
    }

    // -- UPDATE ---------------------------------------------------------

    /** @ORM\PreUpdate */
    public function preUpdateHandler(Media $entity, PreUpdateEventArgs $args)
    {
        $entity->preUpload();
        //dd($args);
    }
    /** @ORM\PostUpdate */
    public function postUpdateHandler(Media $entity, LifecycleEventArgs $args)
    {
        $entity->upload();
    }

    // -- REMOVE ---------------------------------------------------------

    /** @ORM\PreRemove */
    public function preRemoveHandler(Media $entity, LifecycleEventArgs $args)
    {
        $entity->preRemoveUpload();
    }
    /** @ORM\PostRemove */
    public function postRemoveHandler(Media $entity, LifecycleEventArgs $args)
    {
        //$entity = $args->getObject();
        $entity->removeUpload();
    }

    // -- FLUSH ----------------------------------------------------------

    /** @ORM\PreFlush */
    public function preFlushHandler(Media $entity, PreFlushEventArgs $args)
    {
    }

    // -- LOAD ----------------------------------------------------------

    /** @ORM\PostLoad */
    public function postLoadHandler(Media $entity, LifecycleEventArgs $args)
    {
        /*$fileName = $entity->getUrl();
        $entity->setFile(new UploadedFile($this->uploader->getTargetDirectory().'/'.$fileName, $entity->getNomMedia()));*/
    }
}