<?php

namespace App\Controller;

use App\Repository\StylistRepository;
use App\Repository\StylistWorksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

// /**
//  * Class StylistWorksController
//  * @package App\Controller
//  * @IsGranted("ROLE_USER")
//  */
class StylistWorksController extends AbstractController
{
    #[Route('/stylist/works/{id}', name: 'app_stylist_works')]
    public function index($id, StylistWorksRepository $stylistWorksRepository, StylistRepository $stylistRepository): JsonResponse
    {
        // find $services and $stylist those id  = {id}
        $services = $stylistWorksRepository->findBy(['stylist' => $id]);
        $stylist = $stylistRepository->findOneBy(['id' => $id]);
        $stylistName = $stylist->getName();

        $servicesArray = [];
        foreach ($services as $service){
            $servicesArray[] = [
                'serviceId' => $service->getId(),
                'serviceName' => $service->getServiceName(),
                'price' => $service->getPrice(),
                'photo' => $service->getPhoto(),
                'time' => $service->getTime(),
                'stylistId' => $service->getStylist()->getId()
            ];
        }

        return new JsonResponse(['servicesArray' => $servicesArray, 'stylistName' => $stylistName]);
    }
}
