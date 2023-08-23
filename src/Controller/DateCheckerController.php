<?php

namespace App\Controller;

use App\Entity\Shedule;
use App\Entity\StylistWorks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        // отримую всі дні які співпадають з днем який обрав користувач
        $existingEntry = $entityManager->getRepository(Shedule::class)->findBy(['day' => new \DateTime($selectedDate)]);
        $busyHours = [];
        foreach ($existingEntry as $entry) {
            $visitTime = $entry->getVisitTime();
            // Отримуємо годину як ціле число
            $hours = (int)$visitTime->format('H');
            $busyHours[] = $hours;
        }
        // Отримати можливий діапазон годин
        $availableHours = range(9, 18);
        // повертає значення які відрізняються в масиві $availableHours від $busyHours
        $availableHours = array_diff($availableHours, $busyHours);
        // фільтрую години, які доступні для резервації,
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


