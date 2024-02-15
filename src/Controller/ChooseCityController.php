<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChooseCityController extends AbstractController
{
    #[Route('/choose/city', name: 'app_choose_city')]
    public function index(CityRepository $cityRepository): Response
    {
        $cities = $cityRepository->findAll();

        return $this->render('choose_city/index.html.twig', [
            'cities' => $cities,
        ]);
    }
}
