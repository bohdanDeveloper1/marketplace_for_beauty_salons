<?php
namespace App\Controller;

use App\Repository\SheduleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReservationDetailsController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class ReservationDetailsController extends AbstractController
{
    // передав об'єкт reservation
    #[Route('/reservation/details', name: 'app_reservation_details')]
    public function index(Request $request, SheduleRepository $sheduleRepository): Response
    {
        // get reservation that user have chosen to show data to user
        $currentReservationId = $request->query->get('reservationId');
        $currentReservation = $sheduleRepository->findOneBy(['id' => $currentReservationId]);


        return $this->render('reservation_details/index.html.twig', [
            'currentReservation' => $currentReservation,
        ]);
    }
}
