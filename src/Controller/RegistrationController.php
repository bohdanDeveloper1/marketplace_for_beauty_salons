<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    // треба створити клас User та RegistrationFormType (ФОРМУ ДЛЯ ВПИСУВАННЯ ДАНИХ) для реєстрації нового користувача.
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        // створення форми
        $form = $this->createForm(RegistrationFormType::class, $user);
        //перевірка чи форма існує
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // перевірка чи вказаний користувачем email вже існує в БД
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', 'An account with this email already exists.');
                return $this->redirectToRoute('app_register');
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // записує введені користувачем дані в базу данних
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Properly registered. You can log in now!'
            );

            return $this->redirectToRoute('app_login');
        } elseif ($form->isSubmitted() && !$form->isValid()) {

            $this->addFlash(
                'warning',
                'Something went wrong during registration process! Please try again!'
            );
        }


        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
