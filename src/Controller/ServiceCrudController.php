<?php

namespace App\Controller;

use App\Entity\Salon;
use App\Entity\Service;
use App\Form\ServiceEditType;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Cassandra\Exception\ExecutionException;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SalonOwnerController
 * @package App\Controller
 * @IsGranted("ROLE_SALON_OWNER")
 */
#[Route('/service/crud')]
class ServiceCrudController extends AbstractController
{
    // {id} = id салону, в якому власник хоче редагувати сервіси
    #[Route('/index/{id}', name: 'app_service_crud_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository, $id, EntityManagerInterface $entityManager, Security $security): Response
    {
        //  отримав об'єкт Salon, перевірив чи салон належить поточному користувачеві (foreignKey = Salon Owner)
        $currentSalon = $entityManager->getRepository(Salon::class)->findOneBy(['id' => $id]);
        $currentSalonOwner = $currentSalon->getSalonOwner();
        $currentSalonOwnerToCheck = $security->getUser();

        if ($currentSalonOwner == $currentSalonOwnerToCheck){
            return $this->render('service_crud/index.html.twig', [
                'services' => $serviceRepository->findBy(['salon' => $id]),
                'salonId' => $id
            ]);
        }else{
            $this->addFlash('danger', 'You are not the owner of a salon, choose a salon from your list');
            return $this->redirectToRoute('app_salon_owner_panel');
        }
    }

    #[Route('/new', name: 'app_service_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceRepository $serviceRepository, Security $security, EntityManagerInterface $entityManager): Response
    {
        $currentSalonOwner = $security->getUser();
        $ownerSalons = $entityManager->getRepository(Salon::class)->findBy(['salonOwner' => $currentSalonOwner]);
        $salonAdresses = [];

        foreach ($ownerSalons as $salon){
            $salonAdresses[] = $salon->getAdress();
        }

        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service, ['salonAdresses' => $salonAdresses]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $photo = $form->get('photo')->getData();
            $salonAdress = $form->get('salonAdress')->getData();
            $salonObject = $entityManager->getRepository(Salon::class)->findOneBy(['adress' => $salonAdress]);

           try{
               $service->setName($name);
               $service->setPhoto($photo);
               $service->setSalon($salonObject);
               $entityManager->persist($service);
               $entityManager->flush();

               $this->addFlash('success', "Service \"$name\" wasn't added");
           }catch (Exception $e){
               $this->addFlash('danger', `Service "{$name}" wasn't added`);
           }
        }

        return $this->render('service_crud/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_service_crud_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('service_crud/show.html.twig', [
            'service' => $service,
        ]);
    }

    // TODO перевірити чи власник має даний сервіс, щоб його змінювати
    #[Route('/{id}/edit', name: 'app_service_crud_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, Service $service, ServiceRepository $serviceRepository, Security $security, EntityManagerInterface $entityManager): Response
    {
        $currentService = $entityManager->getRepository(Service::class)->findOneBy(['id' => $id]);
        $currentSalon = $entityManager->getRepository(Salon::class)->findOneBy(['id' => $currentService->getSalon()]);
        $currentSalonOwner = $currentSalon->getSalonOwner();
        $currentSalonOwnerToCheck= $security->getUser();

        if ($currentSalonOwner !== $currentSalonOwnerToCheck){
            $this->addFlash('danger', 'You haven`t opportunity to edit selected service');
            return   $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ServiceEditType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $photo = $form->get('photo')->getData();
            $serviceObject = $entityManager->getRepository(Service::class)->findOneBy(['id' => $id]);
            $salonId = $serviceObject->getSalon();
            $salonObject =   $entityManager->getRepository(Salon::class)->findOneBy(['id' => $salonId]);

            try{
                $service->setName($name);
                $service->setPhoto($photo);
                $service->setSalon($salonObject);
                $entityManager->persist($service);
                $entityManager->flush();

                $this->addFlash('success', `Service "{$name}" was added`);
            }catch (Exception $e){
                $this->addFlash('danger', `Service "{$name}" wasn't added`);
            }
        }

        return $this->render('service_crud/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_crud_delete')]
    public function delete($id, Service $service, EntityManagerInterface $entityManager , ServiceRepository $serviceRepository): Response
    {
        // отримати ід салону та перенаправити на  app_service_crud_index
        $serviceObject = $entityManager->getRepository(Service::class)->findOneBy(['id' => $id]);
        $salonObject = $entityManager->getRepository(Salon::class)->findOneBy(['id' => $serviceObject->getSalon()]);
        $salonId = $salonObject->getId();

        $serviceRepository->remove($service, true);

        return $this->redirectToRoute('app_service_crud_index', ['id' => $salonId], Response::HTTP_SEE_OTHER);
    }
}
