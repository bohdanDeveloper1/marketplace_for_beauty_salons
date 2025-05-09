<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/city/manager')]
class CityCrudController extends AbstractController
{
    #[Route('/', name: 'app_city_crud_index', methods: ['GET'])]
    public function index(CityRepository $cityRepository): Response
    {
        return $this->render('city_crud/index.html.twig', [
            'cities' => $cityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_city_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CityRepository $cityRepository): Response
    {
        return $this->render('city_crud/new.html.twig');
    }

    #[Route('/add/city', name: 'app_city_crud_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $cityName = $request->request->get('cityName');
        $cityPhoto = $request->files->get('cityPhoto');
        $cityCoordinatesLat = $request->request->get('cityCoordinatesLat');
        $cityCoordinatesLon = $request->request->get('cityCoordinatesLon');

        // робота з фото
        try {
            // отримую ім'я фото
            $cityPhotoName = 'photoOf' . $cityPhoto->getClientOriginalName();
            // додаю фото до папки images/cities/
            $cityPhoto->move('images/cities/', $cityPhotoName);

            // add photo link to DB
            $city = new City();
            $city->setName($cityName);
            $city->setPhoto('images/cities/' . $cityPhotoName);
            $city->setCoordinatesLatitude($cityCoordinatesLat);
            $city->setCoordinatesLongtitude($cityCoordinatesLon);

            $entityManager->persist($city);
            $entityManager->flush();

            return new JsonResponse(['ifCityAdded' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['ifCityAdded' => false, 'Exception' => $e]);
        }
    }

    #[Route('/{id}', name: 'app_city_crud_show', methods: ['GET'])]
    public function show(City $city): Response
    {
        return $this->render('city_crud/show.html.twig', [
            'city' => $city,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_city_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, City $city, CityRepository $cityRepository): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cityRepository->save($city, true);

            return $this->redirectToRoute('app_city_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('city_crud/edit.html.twig', [
            'city' => $city,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_city_crud_delete', methods: ['POST'])]
    public function delete(Request $request, City $city, CityRepository $cityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$city->getId(), $request->request->get('_token'))) {
            $cityRepository->remove($city, true);
        }

        return $this->redirectToRoute('app_city_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
