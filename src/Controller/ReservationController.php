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

// /**
//  * Class DateCheckerController
//  * @package App\Controller
//  * @IsGranted("ROLE_USER")
//  */
class ReservationController extends AbstractController
{
    // TODO отримати дані від компонента Vue, перевірити чи досі вільна обрана година, записати резервацію в БД
    #[Route('/reservation', name: 'app_reserve')]
    public function reserve(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $stylistWorkId = $request->request->get('stylistWorkId');
        $stylistId = $request->request->get('stylistId');
        $formatedDateInString = $request->request->get('formatedDate');
        $startTime = $request->request->get('startTime');
        $endTime = $request->request->get('endTime');

        $currentStylistWork = $entityManager->getRepository(StylistWorks::class)->findOneBy(['id' => $stylistWorkId]);
        $currentStylist = $entityManager->getRepository(Stylist::class)->findOneBy(['id' => $stylistId]);
        $dateInDateFormat = \DateTime::createFromFormat('d/m/Y', $formatedDateInString);
        $startTimeInDateFormat = new \DateTime();
        $startTimeInDateFormat->setTime($startTime, 0, 0);
        $endTimeTimeInDateFormat = new \DateTime();
        $endTimeTimeInDateFormat->setTime($endTime, 0, 0);

        $response = 'Reservation was added';

        try{
            // make reservation in table shedule in database
            $newEntry = new Shedule();
            $newEntry->setServiceName($currentStylistWork->getServiceName());
            $newEntry->setServicePrice($currentStylistWork->getPrice());
            // $newEntry->setUserEmail($security->getUser()->getEmail());
            $newEntry->setUserEmail('testEmail');
            $newEntry->setStylistWork($currentStylistWork);
            $newEntry->setDuration($currentStylistWork->getTime());
            $newEntry->setDay($dateInDateFormat);
            $newEntry->setstartTime($startTimeInDateFormat);
            $newEntry->setEndTime($endTimeTimeInDateFormat);
            $newEntry->setStylistId($stylistId);
            // треба перетворити годину в time

            $entityManager->persist($newEntry);
            $entityManager->flush();
        }catch (\Exception $exception){
            error_log('Error: ' . $exception->getMessage());
            error_log('Trace: ' . $exception->getTraceAsString());
            $response = 'problem with adding reservation';
        }

        return new JsonResponse(['ifReservationAdded' => $response]);
    }
}
