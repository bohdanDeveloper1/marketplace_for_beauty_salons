<?php

namespace App\Controller;

use App\Repository\SalonRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalonChooseController extends AbstractController
{
    #[Route('/salon/choose', name: 'app_salon_choose')]
    public function index(Request $request, SalonRepository $salonRepository, ServiceRepository $serviceRepository): Response
    {
        $searchText = $request->request->get('search');
        $salons = [];

        if ($searchText !== '') {
            $salons = $salonRepository->findSalonsBySearchText($searchText);
        } else {
            $salons = $salonRepository->findAll();
        }

        $services = $serviceRepository->findAll();

        return $this->render('salon_choose/index.html.twig', [
            'salons' => $salons,
            'services' => $services
        ]);
    }
}
