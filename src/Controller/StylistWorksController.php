<?php

namespace App\Controller;

use App\Repository\StylistRepository;
use App\Repository\StylistWorksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StylistWorksController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class StylistWorksController extends AbstractController
{
    #[Route('/stylist/works/{id}', name: 'app_stylist_works')]
    // get $stylist_id to find data about stylist
    public function index($id, StylistWorksRepository $stylistWorksRepository, StylistRepository $stylistRepository): Response
    {
        // треба шукати ті, які відносяться до конкретного стиліста
        // find $services and $stylist those id  = {id}
        $services = $stylistWorksRepository->findBy(['stylist' => $id]);
        $stylists = $stylistRepository->findBy(['id' => $id]);

        return $this->render('stylist_works/index.html.twig', [
            'services' => $services,
            'stylists' => $stylists,
        ]);
    }
}
