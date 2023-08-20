<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TermsOfServiceController extends AbstractController
{
    #[Route('/terms_of_service', name: 'app_terms_of_service')]
    public function showTerms() : Response
    {

        return $this->render('terms_of_service/terms_of_service.html.twig');
    }
}