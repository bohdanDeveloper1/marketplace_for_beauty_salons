<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserAjaxController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils, Security $security): JsonResponse
    {
        $email = $request->request->get('email', '');

        if ($email !== '') {
            $getUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($getUser) {
                return new JsonResponse(['success' => 1, 'email' => $getUser->getEmail(), 'password' => $getUser->getPassword()]);
            } else {
                return new JsonResponse(['success' => 0, 'error' => "Invalid Email"]);
            }
        } else {
            return new JsonResponse(['success' => 0, 'error' => "Email cannot be empty"]);
        }
    }
}