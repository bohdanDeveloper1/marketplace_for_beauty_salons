<?php

namespace App\Controller;

use App\Entity\Salon;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SalonOwnerController
 * @package App\Controller
 * @IsGranted("ROLE_SALON_OWNER")
 */
class SalonOwnerController extends AbstractController
{
    #[Route('/salon/owner/panel', name: 'app_salon_owner_panel')]
    public function index(Security $security, EntityManagerInterface $entityManager): Response
    {
        $owner= $security->getUser();
        $ownerSalons = $entityManager->getRepository(Salon::class)->findBy(['salonOwner' => $owner]);

        // передав об'єкти салонів до шаблону
        return $this->render('salon_owner/index.html.twig', [
            'ownerSalons' => $ownerSalons,
        ]);
    }
}
