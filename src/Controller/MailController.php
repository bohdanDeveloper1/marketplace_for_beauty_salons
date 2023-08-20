<?php

namespace App\Controller;

use App\Service\MailAfterReservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
//    #[Route('/mail', name: 'app_mail')]
//    public function index(): Response
//    {
//        return $this->render('mail/index.html.twig', [
//            'controller_name' => 'MailController',
//        ]);
//    }

    #[Route('/mail/send', name: 'app_mail')]
    public function new(MailAfterReservation $mailAfterReservation): Response
    {
        if ($mailAfterReservation->notifyOfSiteUpdate()) {
            $this->addFlash('success', 'Notification mail was sent successfully.');
        }
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }
}
