<?php

namespace App\Controller;

use App\Repository\SheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\StylistWorks;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


// /**
//  * Class ReservationDetailsController
//  * @package App\Controller
//  * @IsGranted("ROLE_USER")
//  */
class ReservationDetailsController extends AbstractController
{
    // передав об'єкт reservation
    #[Route('/confirmation', name: 'app_confirmation')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $chosenServiceId = $request->request->get('serviceId');
        $chosenServiceObject = $entityManager->getRepository(StylistWorks::class)->findOneBy(['id' => $chosenServiceId]);
        $stylistObject = $chosenServiceObject->getStylist();

        $chosenServiceData = [
            'serviceName' => $chosenServiceObject->getServiceName(),
            'price' => $chosenServiceObject->getPrice(),
            'photo' => $chosenServiceObject->getPhoto(),
            'duration'  => $chosenServiceObject->getTime(),
            'stylistName' => $stylistObject->getName()
        ];

        return new JsonResponse(['chosenServiceData' => $chosenServiceData]);
    }
}
