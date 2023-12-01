<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomMedia = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cheminMedia = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numMedia = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extension = null;

    /** @var UploadedFile $file */
    private $file;
    
    private $tempFilename;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMedia(): ?string
    {
        return $this->nomMedia;
    }

    public function setNomMedia(?string $nomMedia): static
    {
        $this->nomMedia = $nomMedia;

        return $this;
    }

    public function getCheminMedia(): ?string
    {
        return $this->cheminMedia;
    }

    public function setCheminMedia(?string $cheminMedia): static
    {
        $this->cheminMedia = $cheminMedia;

        return $this;
    }

    public function getNumMedia(): ?string
    {
        return $this->numMedia;
    }

    public function setNumMedia(?string $numMedia): static
    {
        $this->numMedia = $numMedia;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }

        /**
     * @param ?UploadedFile $file - Uploaded File
     */
    public function setFile(?UploadedFile $file)
    {
        $this->file = $file;

        // Remplacement d'un fichier ? Vérifiez si nous avons déjà un fichier pour cette entité
        if (null !== $this->extension)
        {
            // Enregistrez l'extension de fichier afin que nous puissions la supprimer plus tard
            $this->tempFilename = $this->extension;

            // Réinitialiser les valeurs
            $this->extension = null;
            $this->nomMedia = null;
        }
    }

    /**
     * @return ?UploadedFile
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function preUpload()
    {
        // Si aucun fichier n'est défini, ne rien faire
        if (null === $this->file)
        {
            return;
        }

        // Le nom du fichier est l'identifiant de l'entité
        // Nous devons également stocker l'extension de fichier
        $this->extension = $this->file->guessExtension();
        // Et nous gardons le nom d'origine
        $this->nomMedia = md5(uniqid());
        $this->cheminMedia =  $this->getUploadRootDir().$this->nomMedia . ".".$this->file->guessExtension();
    }

    public function upload()
    {
        // Si aucun fichier n'est défini, ne rien faire
        if (null === $this->file)
        {
            return;
        }

        // Un fichier est présent, supprimez-le
        if (null !== $this->tempFilename)
        {
            $oldFile = $this->getUploadRootDir().$this->nomMedia.'.'.$this->tempFilename;
            if (file_exists($oldFile))
            {
                unlink($oldFile);
            }
        }

        // Déplacez le fichier vers le dossier de téléchargement
        $this->file->move(
            $this->getUploadRootDir(),
            $this->nomMedia.'.'.$this->extension
        );
    }

    public function preRemoveUpload()
    {
        // Enregistrez le nom du fichier que nous voudrions supprimer
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
    }

    public function removeUpload()
    {
        // PostRemove => Nous n'avons plus l'identifiant de l'entité => Utilisez le nom que nous avons enregistré
        if (file_exists($this->tempFilename))
        {
            // Remove file
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        // Répertoire de téléchargement
        if($this->getProduit()) {
            return 'uploads/medias/produit/' . $this->getProduit()->getId() . '/';
            // Ça signifie /public/uploads/medias/produit/
        }else{
            return 'uploads/medias/societe/';
        }
        // if($this->getLogoSociete() or $this->getEnteteSociete() or $this->getPiedDePageSociete()) {
        //     return 'uploads/medias/societe/';
        // }
        
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        // Emplacement de l'image (PHP)
        return __DIR__.'/../../public/'.$this->getUploadDir();
    }

    public function getUrl()
    {
        return $this->nomMedia.'.'.$this->extension;
    }
}
