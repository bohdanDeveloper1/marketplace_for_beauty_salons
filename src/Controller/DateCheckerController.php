<?php

namespace App\Controller;

use App\Entity\Shedule;
use App\Entity\StylistWorks;
use App\Repository\StylistRepository;
use App\Service\ComputedFreeHoursService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
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
    public function FindAllFreeHours(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $date = $request->request->get('date');
        $serviceId = $request->request->get('serviceId');

        $currentService = $entityManager->getRepository(StylistWorks::class)->findOneBy(['id' => $serviceId]);
        $stylistId = $currentService->getStylist()->getId();

        $reservationsInSelectedDay = $entityManager->getRepository(Shedule::class)->findBy([
            'day' => DateTime::createFromFormat('Y-m-d', $date),
            'stylistId' => $stylistId
        ]);

        // вже зарезервований час
        $reservedTime = [];
        foreach ($reservationsInSelectedDay as $reservation) {
            // масив заповнюємо типом данних time
            $reservedTime[] = [$reservation->getstartTime(), $reservation->getEndTime()];
        }
        $computedFreeHoursService = new ComputedFreeHoursService();
        $freeHours = $computedFreeHoursService->getHours($reservedTime, $currentService->getTime() / 60);

        return new JsonResponse([
            'freeHoursResponse' => $freeHours
        ]);
    }

    #[Route('/date/checker/confirmation', name: 'app_date_checker_confirmation')]
    public function checkIfSelectedHourStillFree(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $date = $request->request->get('date');
        $serviceId = $request->request->get('serviceId');
        $chosenHourStart = $request->request->get('chosenHourStart');

        $currentService = $entityManager->getRepository(StylistWorks::class)->findOneBy(['id' => $serviceId]);
        $stylistId = $currentService->getStylist();

        $chosenHourEnd = $chosenHourStart + $currentService->getTime() / 60;
        $chosenHour = [$chosenHourStart, $chosenHourEnd];

        $reservationsInSelectedDay = $entityManager->getRepository(Shedule::class)->findBy([
            'day' => DateTime::createFromFormat('Y-m-d', $date),
            'stylistId' => $stylistId
        ]);

        // вже зарезервований час
        $reservedTime = [];
        foreach ($reservationsInSelectedDay as $reservation) {
            // масив заповнюємо типом данних time
            $reservedTime[] = [$reservation->getstartTime(), $reservation->getEndTime()];
        }

        $computedFreeHoursService = new ComputedFreeHoursService();
        $freeHours = $computedFreeHoursService->getHours($reservedTime, $currentService->getTime() / 60);

        function ifChosenHourAvaliable($chosenHour, $freeHours)
        {
            if (!empty($chosenHour)) {
                foreach ($freeHours as $hour) {
                    if ($hour[0] == $chosenHour[0] && $hour[1] == $chosenHour[1]) {
                        return true;
                    }
                }
            }
            return false;
        }

        $ifChosenHourInFreeHours = ifChosenHourAvaliable($chosenHour, $freeHours);

        return new JsonResponse([
            'ifChosenHourInFreeHours' => $ifChosenHourInFreeHours,
        ]);
    }
}

