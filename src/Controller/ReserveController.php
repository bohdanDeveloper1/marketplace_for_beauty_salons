<?php

namespace App\Controller;

use App\Entity\Shedule;
use App\Entity\StylistWorks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class ReserveController extends AbstractController
{
    #[Route('/reserve', name: 'app_reserve')]
    public function reserve(Request $request, EntityManagerInterface $entityManager): JsonResponse
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

        dump($stylist);  // Додайте цей рядок
        if (!$stylist instanceof StylistWorks) {
            return $this->json(['error' => 'Invalid stylist selected.']);
        }

         // Збереження резервації в базу даних
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

        $message = ["reservation was added on", $selectedHour];

        return $this->json(['message' => $message]);
    }
}
