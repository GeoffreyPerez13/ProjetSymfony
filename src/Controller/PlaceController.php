<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\Picture;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

#[Route('/place')]
#[IsGranted('ROLE_USER')]
class PlaceController extends AbstractController
{
    #[Route('/', name: 'app_place_index', methods: ['GET'])]
    public function index(PlaceRepository $placeRepository): Response
    {
        $user = $this->getUser();
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findBy(['user'=> $user->getId()]),
        ]);
    }

    #[Route('/new', name: 'app_place_new', methods: ['GET', 'POST'])]
    #[Route('/edit/{id}', name: 'app_place_edit', methods: ['GET', 'POST'])]
    public function new(Place $place=null, Request $request, FileUploader $fileUploader, PlaceRepository $placeRepository, SluggerInterface $sluggerInterface): Response
    {
        if($place==null)
            $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
 
            $place->setUser($this->getUser());

            $pictures = $place->getPictures();

            if ($pictures) {
                foreach($pictures as $picture)
                {
                    if(!empty($picture->getPictureFile()))
                    {
                        $filename = $fileUploader->upload($picture->getPictureFile(), 'place');
                        // Si on avait déjà une image (remplacement)
                        if(!empty($picture->getFile()))
                            $fileUploader->remove($picture->getFile(),'place'); //on supprime l'ancienne sur le disque
                        $picture->setFile($filename);
                    }
                }
            }

            $placeRepository->save($place, true);

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('place/new.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_place_delete', methods: ['POST'])]
    public function delete(Request $request, Place $place, PlaceRepository $placeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $placeRepository->remove($place, true);
        }

        return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
    }
}
