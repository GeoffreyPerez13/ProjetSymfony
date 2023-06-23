<?php

namespace App\Controller;

use App\Entity\Place;
use Twig\Environment;
use App\Repository\PlaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function noLocalIndex(PlaceRepository $placeRepository): Response
    {
        return $this->redirectToRoute('home_local', ['_locale'=>'fr']);
    }

    #[Route('/{_locale<%app.supported_locales%>}', name: 'home_local')]
    public function index(PlaceRepository $placeRepository): Response
    {
        return $this->render('front/index.html.twig', [
            'places' => $placeRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    #[Route('/api/search/{keyword?}', name: 'app_api_search')]
    public function searchApi(?string $keyword, PlaceRepository $placeRepository): Response
    {
        $places = $placeRepository->findBySearchKeywords($keyword);

        return $this->json($places, 200, [], ['groups' => 'search']);
    }

    #[Route('/search', name: 'app_search', methods: ['POST'])]
    public function search(PlaceRepository $placeRepository, Request $request): Response
    {
        $keyword = $request->request->get('keyword','');
        return $this->render('front/searchResults.html.twig', [
            'places' => $placeRepository->findBySearchKeywords($keyword),
            'keyword' => $keyword

        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/view/{slug?}', name: 'app_view')]
    public function placeView(Place $place=null): Response
    {
        if($place==null)
            throw new NotFoundHttpException();

        return $this->render('front/view.html.twig', [
            'place' => $place
        ]);
    }


    #[Route('/{_locale<%app.supported_locales%>}/pages/{page}', name: 'app_static_page', requirements: ['page' => '[a-z]+'])]
    public function staticPage(string $_locale, string $page, Environment $twig): Response
    {
        $template = 'front/pages/' . $page . '.'. $_locale .'.html.twig';
        $loader = $twig->getLoader();
        if (!$loader->exists($template))
            throw new NotFoundHttpException();

        return $this->render($template, ['page' => $page]);
    }
}
