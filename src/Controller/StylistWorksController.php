<?php

namespace App\Controller;

use App\Repository\StylistRepository;
use App\Repository\StylistWorksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylistWorksController extends AbstractController
{
    #[Route('/stylist/works/{id}', name: 'app_stylist_works')]
    // приймаю $stylist_id щоб знайти дані про конкретного стиліста
    public function index($id, StylistWorksRepository $stylistWorksRepository, StylistRepository $stylistRepository): Response
    {
        // треба шукати ті, які відносяться до конкретного стиліста
        $services = $stylistWorksRepository->findBy(['stylist' => $id]);
        $stylists = $stylistRepository->findBy(['id' => $id]);

        return $this->render('stylist_works/index.html.twig', [
            'services' => $services,
            'stylists' => $stylists,
        ]);
    }
}
