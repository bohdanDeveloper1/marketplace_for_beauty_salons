<?php

namespace App\Controller;

use App\Repository\SheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class VueComponentsController extends AbstractController
{
    #[Route('/vue/component', name: 'app_vue_component')]
    public function index(): Response
    {
        return $this->render('vue_components/test_component.html.twig');
    }
}

