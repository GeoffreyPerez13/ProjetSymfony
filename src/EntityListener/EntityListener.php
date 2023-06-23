<?php

namespace App\EntityListener;

use App\Entity\Place;
use App\Entity\Picture;
use Doctrine\ORM\Events;
use App\Service\FileUploader;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::prePersist, entity: Place::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Place::class)]
#[AsEntityListener(event: Events::postRemove, entity: Picture::class)]
class EntityListener
{
    public function __construct(
        private SluggerInterface $slugger,
        private FileUploader $fileUploader
    ) {
    }

    public function prePersist(Place $place, LifecycleEventArgs $event)
    {
        $this->generateSlug($place);
    }

    public function preUpdate(Place $place, LifecycleEventArgs $event)
    {
        $this->generateSlug($place);
    }

    public function postRemove(Picture $picture, LifecycleEventArgs $event)
    {
        $this->fileUploader->remove($picture->getFile(),'place');
    }


    private function generateSlug($place) {
        $place->setSlug($this->slugger->slug($place->getName())->lower());
    }
}