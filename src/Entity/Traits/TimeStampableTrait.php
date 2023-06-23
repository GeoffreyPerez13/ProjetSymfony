<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


trait TimeStampableTrait {

    #[ORM\Column]
    #[Groups(['search'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifiedAt = null;


    #[ORM\PrePersist]
    public function generateCreatedAt()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    #[ORM\PreUpdate]
    public function generateModifiedAt()
    {
        $this->setModifiedAt(new \DateTimeImmutable());
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeImmutable $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;
        return $this;
    }

}
