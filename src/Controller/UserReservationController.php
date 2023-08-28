<?php

namespace App\Controller;

use App\Repository\SheduleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserReservationController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class UserReservationController extends AbstractController
{
    #[Route('/user/reservation', name: 'app_user_reservation')]
    public function index(SheduleRepository $shedule): Response
    {
        // only to AUTHENTICATED users
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userEmail = $user->getEmail();

        // all current user reservations
        $userReservations = $shedule->findBy(['userEmail' => $userEmail]);

        return $this->render('user_reservation/index.html.twig', [
            'userReservations' => $userReservations,
        ]);
    }
}
