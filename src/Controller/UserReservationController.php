<?php

namespace App\Controller;

use App\Repository\SheduleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

/**
 * Class UserReservationController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class UserReservationController extends AbstractController
{
    #[Route('/watch/reservations', name: 'app_watch_reservations')]
    public function index(Security $security, SheduleRepository $shedule): Response
    {
        $userEmail = $security->getUser()->getEmail();

        // all current user reservations
        $userReservations = $shedule->findBy(['userEmail' => $userEmail]);

        return $this->render('user_reservation/index.html.twig', [
            'userReservations' => $userReservations,
        ]);
    }
}
