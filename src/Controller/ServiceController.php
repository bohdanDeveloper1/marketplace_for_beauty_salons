<?php
namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

 /**
  * Class ServiceController
  * @package App\Controller
  * @IsGranted("ROLE_USER")
  */
class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();
        $servicesArray = [];
        foreach ($services as $service) {
            $servicesArray[] = [
                'id' => $service->getId(),
                'name' => $service->getName(),
                'photo' => $service->getPhoto(),
            ];
        }

         return $this->render('service/index.html.twig', [
            'servicesArray' => $servicesArray,
        ]);
//        return new JsonResponse(['servicesArray' => $servicesArray]);
    }
}
