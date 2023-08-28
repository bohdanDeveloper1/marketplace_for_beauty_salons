<?php

namespace App\Controller;

use App\Repository\StylistRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StylistController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class StylistController extends AbstractController
{
    #[Route('/stylist/{id}', name: 'app_stylist')]
    public function getHairdressers($id, StylistRepository $stylistRepository): Response
    {
        $stylists = $stylistRepository->findBy(['category' => $id]);

        return $this->render('stylist/index.html.twig', [
            'stylists' => $stylists,
        ]);
    }
}
