<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Salon;
use App\Repository\SalonRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalonChooseController extends AbstractController
{
    #[Route('/salon/choose/{cityId}', name: 'app_salon_choose')]
    public function index(Request $request, $cityId, SalonRepository $salonRepository, ServiceRepository $serviceRepository, EntityManagerInterface $entityManager): Response
    {
        // координати міста, всі салони, сервіси салонів, інформація про салони (назва, координати, послуги
        $currentCity = $entityManager->getRepository(City::class)->findOneBy(['id' => $cityId]);
        $currentCityName = $currentCity->getName();
        $searchText = $request->request->get('search');

        if ($searchText !== '') {
            $salons = $salonRepository->findSalonsBySearchText($searchText, $cityId);
        } else {
            // знаходжу всі салони у вибраному місті: cityId
            $salons = $entityManager->getRepository(Salon::class)->findBy(['belongToCity' => $cityId]);
        }

        $services = $serviceRepository->findAll();

        $currentCityCoordinates = [$currentCity->getCoordinatesLatitude(), $currentCity->getCoordinatesLongtitude()];
        // масив з даними про всі салони в місті
        $salonsInCurrentCityData = [];
        foreach ($salons as $salon) {
            $salonsInCurrentCityData[] = [
                'name' => $salon->getName(),
                'adress' => $salon->getAdress(),
                'description' => $salon->getDescription(),
                'coordinatesLat' => $salon->getCoordinatesLatitude(),
                'coordinatesLon' => $salon->getCoordinatesLongtitude()
            ];
        }

        return $this->render('salon_choose/index.html.twig', [
            'salons' => $salons,
            'services' => $services,
            'cityId' => $cityId,
            'currentCityName'=> $currentCityName,
            'currentCityCoordinates' => json_encode($currentCityCoordinates),
            'salonsInCurrentCityData' => json_encode($salonsInCurrentCityData),
        ]);
    }
}
