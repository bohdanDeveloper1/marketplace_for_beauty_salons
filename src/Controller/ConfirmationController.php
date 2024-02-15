<?php

namespace App\Controller;

use App\Repository\SheduleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\StylistWorks;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


 /**
  * Class ConfirmationController
  * @package App\Controller
  * @IsGranted("ROLE_USER")
  */
class ConfirmationController extends AbstractController
{
    #[Route('/confirmation/{id}/{startTime}/{date}', name: 'app_confirmation', requirements: [
        'date' => '\d{4}-\d{2}-\d{2}'
    ])]
    public function index($id, $startTime, $date, EntityManagerInterface $entityManager, Security $security): Response
    {
        $chosenServiceObject = $entityManager->getRepository(StylistWorks::class)->findOneBy(['id' => $id]);
        $stylistObject = $chosenServiceObject->getStylist();
        $endTime = $startTime + ($chosenServiceObject->getTime() / 60);

        $chosenServiceData = [
            'serviceId' => $chosenServiceObject->getId(),
            'serviceName' => $chosenServiceObject->getServiceName(),
            'price' => $chosenServiceObject->getPrice(),
            'photo' => $chosenServiceObject->getPhoto(),
            'duration'  => $chosenServiceObject->getTime(),
            'stylistName' => $stylistObject->getName(),
            'stylistId' => $stylistObject->getId()
        ];

        $dateData = [
            'startTime' => $startTime,
            'endTime' => $endTime,
            'date' => $date
        ];

        return $this->render('confirmation/index.html.twig', [
            'chosenServiceData' => $chosenServiceData,
            'dateData' => $dateData,
            'userEmail' =>$security->getUser()->getEmail()
        ]);
    }
}
