<?php

namespace App\Controller;

use App\Entity\Shedule;
use App\Entity\StylistWorks;
use App\Repository\StylistRepository;
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

// /**
//  * Class DateCheckerController
//  * @package App\Controller
//  * @IsGranted("ROLE_USER")
//  */
class DateCheckerController extends AbstractController
{
    #[Route('/date/checker', name: 'app_date_checker')]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $selectedDate = $request->request->get('date');
        $date = \DateTime::createFromFormat('d/m/Y', $selectedDate);
        $serviceId = $request->request->get('serviceId');
        $chosenHourStart = $request->request->get('chosenHourStart');
        $chosenHourEnd = $request->request->get('chosenHourEnd');
        $chosenHour = [$chosenHourStart,  $chosenHourEnd];


        $currentService = $entityManager->getRepository(StylistWorks::class)->findOneBy(['id' => $serviceId]);
        $stylistId = $currentService->getStylist();



        $reservationsInSelectedDay = $entityManager->getRepository(Shedule::class)->findBy([
            'day' => $date,
            'stylistId' => $stylistId
        ]);

        $reservedTime = []; // вже зарезервований час
        foreach ($reservationsInSelectedDay as $reservation){
            // масив заповнюємо типом данних time
            $reservedTime[] = [$reservation->getstartTime(),  $reservation->getEndTime()];
        }
        $openTime = 9;
        $closeTime = 18;
        $duration = $currentService->getTime() / 60;

        function isSlotFree($slotStart, $slotEnd, $reserved)
        {
            foreach ($reserved as [$start, $end]) {
                if (!($slotEnd <= $start || $slotStart >= $end)) {
                    return false; // Часовий проміжок не вільний
                }
            }
            return true; // Часовий проміжок вільний
        }
        // find free hours
        function getHours($reserved, $openTime, $closeTime, $duration)
        {
            $freeHours = [];
            // Перебираємо всі години від $openTime до $closeTime - $duration
            for ($i = $openTime; $i <= $closeTime - $duration; $i++) {
                if (isSlotFree($i, $i + $duration, $reserved)) {
                    $freeHours[] = [$i, $i + $duration];
                }
            }
            return $freeHours;
        }
        $freeHours = getHours($reservedTime, $openTime, $closeTime, $duration);

        function ifChosenHourAvaliable($chosenHour, $freeHours){
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
            'freeHoursResponse' => $freeHours,
            'ifChosenHourInFreeHours' => $ifChosenHourInFreeHours,
        ]);
    }
}


