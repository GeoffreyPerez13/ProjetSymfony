<?php

namespace App\EventSubscriber;

use App\Repository\PlaceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;


class TwigEventSubscriber implements EventSubscriberInterface
{

    private $twig;
    private $placeRepository;

    public function __construct(Environment $twig, PlaceRepository $placeRepository)
    {
        $this->twig = $twig;
        $this->placeRepository = $placeRepository;
    }
    public function onKernelController(ControllerEvent $event): void
    {
        $this->twig->addGlobal('randomPlaces', $this->placeRepository->findRandomPlaces(2));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
