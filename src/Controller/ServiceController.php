<?php
//
//namespace App\Controller;
//
//
//use App\Repository\ServiceRepository;
//
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//
//class ServiceController extends AbstractController
//{
//    #[Route('/service', name: 'app_service')]
//    public function index(EntityManagerInterface $entityManager): Response
//    {
//        // масив $service присвоює інформацію із ServiceRepository $service_name та $service_photo
//        $service = $entityManager->getRepository(ServiceRepository::class)->findAll();
//
//        return $this->render('service/index.html.twig', [
//            'service' =>  $service
//        ]);
//    }
//}

// src/Controller/ServiceController.php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();

        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }
}
