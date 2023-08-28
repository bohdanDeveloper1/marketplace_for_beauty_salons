<?php

namespace App\Controller;

use App\Entity\Shedule;
use App\Entity\StylistWorks;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use  DateTime;
use DateInterval;

/**
 * Class DateCheckerController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class DateCheckerController extends AbstractController
{
    #[Route('/date/checker', name: 'app_date_checker')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $selectedDate = $request->query->get('selectedDate');
        $selectedServiceTimeInMinutes = $request->query->get('selectedServiceTime');
        $selectedServiceStylistId = $request->query->get('selectedServiceStylistId');
        $stylist = $entityManager->getRepository(StylistWorks::class)->findOneBy(['stylist' => $selectedServiceStylistId]);
        $selectedServiceName = $request->query->get('selectedServiceName');
        $selectedServicePrice = $request->query->get('selectedServicePrice');

        // array that get all days that === with chosen day
        $existingEntry = $entityManager->getRepository(Shedule::class)->findBy(['day' => new \DateTime($selectedDate)]);
        $busyHours = [];
        foreach ($existingEntry as $entry) {
            $visitTime = $entry->getVisitTime();
            // convert $visitTime to int
            $hours = (int)$visitTime->format('H');
            $busyHours[] = $hours;
        }
        $availableHours = range(9, 18);
        // return hours that !=  $busyHours
        $availableHours = array_diff($availableHours, $busyHours);
        // filtration hours that are available
        $availableHours = array_filter($availableHours, function($hour) use ($selectedServiceTimeInMinutes) {
            return $hour * 60 + $selectedServiceTimeInMinutes <= 18 * 60;
        });

        return $this->render('date_checker/index.html.twig', [
            'availableHours' => $availableHours,
            'selectedDate' => $selectedDate,
            'selectedServiceStylist' => $stylist,
            'selectedServiceName' => $selectedServiceName,
            'selectedServicePrice' => $selectedServicePrice,
            'selectedServiceTimeInMinutes' => $selectedServiceTimeInMinutes
        ]);
    }
}


