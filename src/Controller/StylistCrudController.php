<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Service;
use App\Entity\Stylist;
use App\Form\StylistType;
use App\Repository\ServiceRepository;
use App\Repository\StylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stylist/crud')]
class StylistCrudController extends AbstractController
{
    //показую всіх стилістів для обраного салону
    #[Route('/info/{salonId}', name: 'app_stylist_crud_index', methods: ['GET'])]
    public function index(StylistRepository $stylistRepository, $salonId, ServiceRepository $serviceRepository): Response
    {
        $servicesInCurrentSalon =  $serviceRepository->findBy(['salon' => $salonId]);
        $allStylists =  $stylistRepository->findAll();
        $currentStylists = [];
        $i = 0;

            foreach ($servicesInCurrentSalon as $service){
                //перевірка чи id сервіса і стиліста співпадають, якщо співпадають, то додаю стиліста в масив
                foreach ($allStylists as $stylist){
                    if($service->getId() == $stylist->getCategory()->getId()){
                        $currentStylists[] = $stylist;
                    }
                 $i++;
                }
        }

        return $this->render('stylist_crud/index.html.twig', [
            'salonId' => $salonId,
            'stylists' => $currentStylists,
        ]);
    }

    #[Route('/new/{salonId}', name: 'app_stylist_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceRepository $serviceRepository, $salonId, EntityManagerInterface $entityManager): Response
    {
        $stylist = new Stylist();
        $categoriesInCurrentSalon = $serviceRepository->findBy(['salon' => $salonId]);

        $categoriesInCurrentSalonNames = [];
        foreach ($categoriesInCurrentSalon as $category){
            $categoriesInCurrentSalonNames[] = $category->getName();
        }

        $form = $this->createForm(StylistType::class, $stylist, ['categoriesInCurrentSalon' => $categoriesInCurrentSalonNames]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stylistName = $form->get('name')->getData();
            $instagram_account = $form->get('instagram_account')->getData();
            $description = $form->get('description')->getData();
            $category = $form->get('category')->getData();

            $categoryObject = $entityManager->getRepository(Service::class)->findOneBy(['name' => $category]);
            /** @var UploadedFile $pictureFileName */
            $pictureFileName = $form->get('photo')->getData();
            try {
                $stylistPhotoName = $stylistName . $category . $instagram_account. 'Salon.' . $pictureFileName->guessExtension();
                // додаю фото до папки 'build/images/salons'
                $pictureFileName->move('images/stylists', $stylistPhotoName);

                $stylist->setCategory($categoryObject);
                $stylist->setName($stylistName);
                $stylist->setInstagramAccount($instagram_account);
                $stylist->setDescription($description);
                $stylist->setPhoto('images/stylists/'.  $stylistPhotoName);

                $entityManager->persist($stylist);
                $entityManager->flush();

                $this->addFlash('success', 'stylist was added');
            } catch (\Exception $e) {
                var_dump($e);
            }
        }

        return $this->render('stylist_crud/new.html.twig', [
            'salonId' => $salonId,
            'stylist' => $stylist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stylist_crud_show', methods: ['GET'])]
    public function show(Stylist $stylist): Response
    {
        return $this->render('stylist_crud/show.html.twig', [
            'stylist' => $stylist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stylist_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stylist $stylist, StylistRepository $stylistRepository): Response
    {
        $form = $this->createForm(StylistType::class, $stylist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stylistRepository->save($stylist, true);

            return $this->redirectToRoute('app_stylist_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stylist_crud/edit.html.twig', [
            'stylist' => $stylist,
            'form' => $form,
        ]);
    }

    #[Route('/delete/stylist/{id}', name: 'app_stylist_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Stylist $stylist, StylistRepository $stylistRepository, ServiceRepository $serviceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stylist->getId(), $request->request->get('_token'))) {
            $stylistRepository->remove($stylist, true);
        }

        $serviceObject = $stylist->getCategory();
        $salonObject = $serviceObject->getSalon();
        $salonId = $salonObject->getId();


        return $this->redirectToRoute('app_stylist_crud_index', ['salonId' => $salonId], Response::HTTP_SEE_OTHER);
    }
}
