<?php

namespace App\Controller\admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServiceController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AddSalonOwnerController extends AbstractController
{
    #[Route('/admin/add/salon/owner', name: 'app_admin_add_salon_owner')]
    public function index(): Response
    {
        return $this->render('add_salon_owner/index.html.twig');
    }
}
