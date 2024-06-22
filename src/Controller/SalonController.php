<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Salon;
use App\Entity\Service;
use App\Entity\User;
use App\Form\SalonType;
use App\Form\SalonUpdateType;
use App\Repository\SalonRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Webmozart\Assert\Tests\StaticAnalysis\email;
use GuzzleHttp\Client;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

/**
 * Class SalonOwnerController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */

#[Route('/salon')]
class SalonController extends AbstractController
{
    #[Route('/', name: 'app_salon_index', methods: ['GET', 'POST'])]
    public function index(SalonRepository $salonRepository, Request $request): Response
    {
        $searchText = $request->request->get('search');
        $salons = [];

        if ($searchText != '') {
            $salons = $salonRepository->findAllSalonsBySearchText($searchText);
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

        $avaliableCities = $entityManager->getRepository(City::class)->findAll();
        $citiesNames = [];

        foreach ($avaliableCities as $city) {
            $citiesNames[] = $city->getName();
        }

        $form = $this->createForm(SalonType::class, $salon, ['avaliableCities' => $citiesNames]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Отримую всі значення з форми
            $salonOwnerEmail = $form->get('salonOwnerEmail')->getData();
            $name =            $form->get('name')->getData();
            $adress =          $form->get('adress')->getData();
            $description =     $form->get('description')->getData();
            $belongToCity =    $form->get('belongToCity')->getData();
            $cityObject = $entityManager->getRepository(City::class)->findOneBy(['name' => $belongToCity]);
            $belongToCityName = $cityObject->getName();
            /** @var UploadedFile $pictureFileName */
            $pictureFileName = $form->get('photo')->getData();
            $salonOwner = $entityManager->getRepository(User::class)->findOneBy(['email' => $salonOwnerEmail]);

            $client = new Client();
            $response = $client->request('GET', 'https://geocode.maps.co/search?q=' . $adress . '+' . $belongToCity . '+' . 'Poland&api_key=659450539ff1f762862410sea796255');
            $body = $response->getBody();
            $data = json_decode($body, true); // декодуємо JSON-відповідь у масив

            if(count($data) > 0) {
                if ($salonOwner) {
                    // створюю ім'я для фото + записую шлях до фото в БД
                    try {
                        $salonPhotoName = 'photoOf' . $adress . 'Salon.' . $pictureFileName->guessExtension();
                        // додаю фото до папки 'build/images/salons'
                        $pictureFileName->move('images/salons', $salonPhotoName);

                        $salon->setName($name);
                        $salon->setAdress($adress);
                        $salon->setDescription($description);
                        $salon->setSalonOwner($salonOwner);
                        $salon->setPhoto('images/salons/' . $salonPhotoName);
                        $salon->setBelongToCity($cityObject);
                        $salon->setCoordinatesLatitude($data[0]['lat']);
                        $salon->setCoordinatesLongtitude($data[0]['lon']);

                        $entityManager->persist($salon);
                        $entityManager->flush();

                        $this->addFlash('success', 'salon was added');
                    } catch (\Exception $e) {
                        var_dump($e);
                    }
                }else{
                    $salonOwnerEmail = $form->get('salonOwnerEmail')->getData();
                    $this->addFlash('danger', "user with such email: {$salonOwnerEmail} does`t exist");
                }
            }else{
                $this->addFlash('danger', 'Please enter correct salon address');
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
    public function edit(Request $request, Salon $salon, $id, EntityManagerInterface $entityManager): Response
    {
        $currentSalon = $entityManager->getRepository(Salon::class)->findOneBy(['id' => $id]);
        $ownerEmail = $currentSalon->getSalonOwner()->getEmail();
        $salonCityName = $currentSalon->getBelongToCity()->getName();
        $avaliableCities = $entityManager->getRepository(City::class)->findAll();
        $citiesNames = [];

        foreach ($avaliableCities as $city) {
            $citiesNames[] = $city->getName();
        }

        $form = $this->createForm(SalonUpdateType::class, $salon, [
            'ownerEmail' => $ownerEmail,
            'salonCityName' => $salonCityName,
            'avaliableCities' => $citiesNames,
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salonOwnerEmail = $form->get('salonOwnerEmail')->getData();
            $name = $form->get('name')->getData();
            $adress = $form->get('adress')->getData();
            $description = $form->get('description')->getData();
            $salonBelongToCity = $form->get('belongToCity')->getData();
            $uploadedFile = $form->get('file')->getData();

            $salonOwner = $entityManager->getRepository(User::class)->findOneBy(['email' => $salonOwnerEmail]);

            if ($uploadedFile) {
                // Створити ім'я файлу для фото салону
                $salonPhotoName = 'photoOf' . $adress . 'Salon.' . $uploadedFile->guessExtension();
                // Зберегти фото до папки
                $uploadedFile->move('images/salons', $salonPhotoName);
                // Встановити новий файл фото для салону
                $salon->setPhoto('images/salons/' . $salonPhotoName);
            }

            if($salonCityName != $salonBelongToCity){
                $cityObject = $entityManager->getRepository(City::class)->findOneBy(['name' => $salonBelongToCity]);
                $salon->setBelongToCity($cityObject);
            }

            // Оновити інші поля салону
            $salon->setName($name);
            // $salon->setAdress($adress);
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
