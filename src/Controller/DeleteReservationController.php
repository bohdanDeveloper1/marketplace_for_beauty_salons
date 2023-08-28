<?php

namespace App\Controller;

use App\Repository\SheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MessageGenerator;

/**
 * Class DeleteReservationController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class DeleteReservationController extends AbstractController
{
    #[Route('/reservation/delete', name: 'app_reservation_delete')]
    public function index(Request $request, SheduleRepository $sheduleRepository, EntityManagerInterface $entityManager, MessageGenerator $messageGenerator): Response
    {
        $reservationToDeleteId = $request->query->get('reservationId');
        $reservationToDelete = $sheduleRepository->findOneBy(['id' => $reservationToDeleteId]);

        if ($reservationToDelete) {
            $entityManager->remove($reservationToDelete);
            $entityManager->flush();

            $message = $messageGenerator->reservationCanceled();
            $this->addFlash('primary', $message);
        }
        return $this->redirectToRoute('app_user_reservation');
    }
}













