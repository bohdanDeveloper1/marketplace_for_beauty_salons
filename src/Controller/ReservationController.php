<?php

namespace App\Controller;

use App\Entity\Shedule;
use App\Entity\Stylist;
use App\Entity\StylistWorks;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use DateTimeImmutable;
use DateTime;

 /**
  * Class DateCheckerController
  * @package App\Controller
  * @IsGranted("ROLE_USER")
  */
class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reserve')]
    public function reserve(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $stylistWorkId = $request->request->get('stylistWorkId');
        $date = $request->request->get('formatedDate');
        $startTime = $request->request->get('startTime');
        $endTime = $request->request->get('endTime');

        $currentStylistWork = $entityManager->getRepository(StylistWorks::class)->findOneBy(['id' => $stylistWorkId]);
        $currentStylist = $entityManager->getRepository(Stylist::class)->findOneBy(['id' => $currentStylistWork->getStylist()]);
        $dateInDateFormat = DateTime::createFromFormat('Y-m-d', $date);

        // make reservation in table shedule in database
        try{
            $newEntry = new Shedule();
            $newEntry->setServiceName($currentStylistWork->getServiceName());
            $newEntry->setServicePrice($currentStylistWork->getPrice());
            $newEntry->setUserEmail($security->getUser()->getEmail());
            $newEntry->setStylistWork($currentStylistWork);
            $newEntry->setDuration($currentStylistWork->getTime());
            $newEntry->setDay($dateInDateFormat);
            $newEntry->setstartTime(intval($startTime));
            $newEntry->setEndTime(intval($endTime));
            $newEntry->setStylistId($currentStylist->getId());

            $entityManager->persist($newEntry);
            $entityManager->flush();
            $response = 'Reservation was successfully added';
        }catch (\Exception $exception){
            error_log('Error: ' . $exception->getMessage());
            error_log('Trace: ' . $exception->getTraceAsString());
            $response = 'problem with adding reservation';
        }

        return new JsonResponse(['ifReservationAdded' => $response]);
    }
}
