<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampableTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PictureRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Picture
{
    use TimeStampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['search'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['search'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['search'])]
    private ?string $file = null;


    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    /**
     * Le fichier upload : pas de lien ORM
     */
    private ?UploadedFile $pictureFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get the value of pictureFile
     */
    public function getPictureFile(): ?UploadedFile
    {
        return $this->pictureFile;
    }

    /**
     * Set the value of pictureFile
     */
    public function setPictureFile(?UploadedFile $pictureFile): self
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }
}
