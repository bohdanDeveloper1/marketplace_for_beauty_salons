<?php

namespace App\Test\Controller;

use App\Entity\Salon;
use App\Repository\SalonRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SalonControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private SalonRepository $repository;
    private string $path = '/salon/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Salon::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Salon index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'salon[name]' => 'Testing',
            'salon[adress]' => 'Testing',
            'salon[description]' => 'Testing',
            'salon[salonOwner]' => 'Testing',
        ]);

        self::assertResponseRedirects('/salon/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Salon();
        $fixture->setName('My Title');
        $fixture->setAdress('My Title');
        $fixture->setDescription('My Title');
        $fixture->setSalonOwner('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Salon');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Salon();
        $fixture->setName('My Title');
        $fixture->setAdress('My Title');
        $fixture->setDescription('My Title');
        $fixture->setSalonOwner('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'salon[name]' => 'Something New',
            'salon[adress]' => 'Something New',
            'salon[description]' => 'Something New',
            'salon[salonOwner]' => 'Something New',
        ]);

        self::assertResponseRedirects('/salon/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getAdress());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getSalonOwner());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Salon();
        $fixture->setName('My Title');
        $fixture->setAdress('My Title');
        $fixture->setDescription('My Title');
        $fixture->setSalonOwner('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/salon/');
    }
}
