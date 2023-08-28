<?php

namespace App\Controller;

use App\Entity\Shedule;
use App\Entity\StylistWorks;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

/**
 * Class ReserveController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class ReserveController extends AbstractController
{
    #[Route('/reserve', name: 'app_reserve')]
    public function reserve(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $selectedDate = $request->query->get('selectedDate');
        $selectedServiceName = $request->query->get('selectedServiceName');
        $selectedServicePrice = $request->query->get('selectedServicePrice');
        $selectedHour = $request->query->get('selectedHour');
        $selectedTime = new \DateTime();
        $selectedTime->setTime($selectedHour, 0); // Встановлюємо годину і хвилини
        $selectedServiceTimeInMinutes = $request->query->get('selectedServiceTimeInMinutes');
        $selectedServiceStylistId = $request->query->get('selectedServiceStylist');
        $stylist = $entityManager->getRepository(StylistWorks::class)->findOneBy(['id' => $selectedServiceStylistId]);
        $userEmail = $request->query->get('userEmail');
        $selectedServiceStylistName =  $request->query->get('selectedServiceStylistName');

         // make reservation in table shedule in database
        $newEntry = new Shedule();
                $newEntry->setStylist($stylist);
                $newEntry->setTime($selectedServiceTimeInMinutes);
                $newEntry->setDay(new \DateTime($selectedDate));
                $newEntry->setServiceName($selectedServiceName);
                $newEntry->setServicePrice($selectedServicePrice);
                $newEntry->setUserEmail($userEmail);
                // треба перетворити годину в time
                $newEntry->setVisitTime($selectedTime);
                $entityManager->persist($newEntry);
                $entityManager->flush();

         return $this->render('reserve/reserve.html.twig', [
            'selectedServiceName' => $selectedServiceName,
            'selectedServicePrice' => $selectedServicePrice,
            'selectedServiceTimeInMinutes' => $selectedServiceTimeInMinutes,
            'selectedDate' => $selectedDate,
            'selectedServiceStylist' => $selectedServiceStylistName
        ]);
    }
}
