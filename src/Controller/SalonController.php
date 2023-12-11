<?php

namespace App\Controller;

use App\Entity\Salon;
use App\Entity\Service;
use App\Entity\User;
use App\Form\SalonType;
use App\Form\SalonUpdateType;
use App\Repository\SalonRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salon')]
class SalonController extends AbstractController
{
    #[Route('/', name: 'app_salon_index', methods: ['GET', 'POST'])]
    public function index(SalonRepository $salonRepository, Request $request): Response
    {
        $searchText = $request->request->get('search');
        $salons = [];

        if ($searchText !== '') {
            $salons = $salonRepository->findSalonsBySearchText($searchText);
        } else {
            $salons = $salonRepository->findAll();
        }

        return $this->render('salon/index.html.twig', [
            'salons' => $salons,
        ]);
    }

    #[Route('/new', name: 'app_salon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SalonRepository $salonRepository, EntityManagerInterface $entityManager): Response
    {
        $salon = new Salon();
        $form = $this->createForm(SalonType::class, $salon);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Отримую всі значення з форми
            $salonOwnerEmail = $form->get('salonOwnerEmail')->getData();
            $name =     $form->get('name')->getData();
            $adress =     $form->get('adress')->getData();
            $description =     $form->get('description')->getData();
            /** @var UploadedFile $pictureFileName */
            $pictureFileName = $form->get('photo')->getData();

            $salonOwner = $entityManager->getRepository(User::class)->findOneBy(['email' => $salonOwnerEmail]);

            if ($salonOwner) {
                // створюю ім'я для фото + записую шлях до фото в БД
                    try {
                        $salonPhotoName = 'photoOf' . $adress . 'Salon.' . $pictureFileName->guessExtension();
                        // додаю фото до папки 'build/images/salons'
                        $pictureFileName->move('build/images/salons', $salonPhotoName);

                        $salon->setName($name);
                        $salon->setAdress($adress);
                        $salon->setDescription($description);
                        $salon->setSalonOwner($salonOwner);
                        $salon->setPhoto('build/images/salons/' . $salonPhotoName);

                        $entityManager->persist($salon);
                        $entityManager->flush();

                        $this->addFlash('success', 'salon was added');
                    } catch (\Exception $e) {
                        $this->addFlash('danger', 'Wystąpił nieoczekiwany błąd!');
                    }
            }else{
                $salonOwnerEmail = $form->get('salonOwnerEmail')->getData();
                $this->addFlash('danger', "user with such email: {$salonOwnerEmail} does`t exist");
            }
        }

        return $this->render('salon/new.html.twig', [
            'salon' => $salon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salon_show', methods: ['GET'])]
    public function show(Salon $salon): Response
    {
        return $this->render('salon/show.html.twig', [
            'salon' => $salon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salon $salon, SalonRepository $salonRepository, EntityManagerInterface $entityManager): Response
    {
        // Створити форму для редагування салону
        $form = $this->createForm(SalonUpdateType::class, $salon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salonOwnerEmail = $form->get('salonOwnerEmail')->getData();
            $name = $form->get('name')->getData();
            $adress = $form->get('adress')->getData();
            $description = $form->get('description')->getData();
            // Перевірити, чи передано новий файл
            $uploadedFile = $form->get('file')->getData();

            $salonOwner = $entityManager->getRepository(User::class)->findOneBy(['email' => $salonOwnerEmail]);

            if ($uploadedFile) {
                // Створити ім'я файлу для фото салону
                $salonPhotoName = 'photoOf' . $adress . 'Salon.' . $uploadedFile->guessExtension();

                // Зберегти фото до папки
                $uploadedFile->move('build/images/salons', $salonPhotoName);

                // Встановити новий файл фото для салону
                $salon->setPhoto('build/images/salons/' . $salonPhotoName);
            }

            // Оновити інші поля салону
            $salon->setName($name);
            $salon->setAdress($adress);
            $salon->setDescription($description);
            $salon->setSalonOwner($salonOwner);

            $entityManager->persist($salon);
            $entityManager->flush();

            $this->addFlash('success', 'Salon was updated');
        }

        return $this->render('salon/edit.html.twig', [
            'salon' => $salon,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_salon_delete')]
    public function delete(Request $request, $id, Salon $salon, SalonRepository $salonRepository, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository): Response
    {
            // видаляю всі сервіси салону перед тим як видалити салон
            $servicesInChosenSalon = $entityManager->getRepository(Service::class)->findBy(['salon' => $id]);
            foreach ($servicesInChosenSalon as $service){
                $serviceRepository->remove($service, true);
            }

            $salonRepository->remove($salon, true);

        $this->addFlash('success', 'salon was deleted');

        return $this->redirectToRoute('app_salon_index', [], Response::HTTP_SEE_OTHER);
    }
}
