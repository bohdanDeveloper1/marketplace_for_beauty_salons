<?php

namespace App\Test\Controller;

use App\Entity\Stylist;
use App\Repository\StylistRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StylistControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private StylistRepository $repository;
    private string $path = '/stylist/crud/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Stylist::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Stylist index');

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
            'stylist[name]' => 'Testing',
            'stylist[instagram_account]' => 'Testing',
            'stylist[description]' => 'Testing',
            'stylist[photo]' => 'Testing',
            'stylist[category]' => 'Testing',
        ]);

        self::assertResponseRedirects('/stylist/crud/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stylist();
        $fixture->setName('My Title');
        $fixture->setInstagram_account('My Title');
        $fixture->setDescription('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Stylist');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stylist();
        $fixture->setName('My Title');
        $fixture->setInstagram_account('My Title');
        $fixture->setDescription('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'stylist[name]' => 'Something New',
            'stylist[instagram_account]' => 'Something New',
            'stylist[description]' => 'Something New',
            'stylist[photo]' => 'Something New',
            'stylist[category]' => 'Something New',
        ]);

        self::assertResponseRedirects('/stylist/crud/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getInstagram_account());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getPhoto());
        self::assertSame('Something New', $fixture[0]->getCategory());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Stylist();
        $fixture->setName('My Title');
        $fixture->setInstagram_account('My Title');
        $fixture->setDescription('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/stylist/crud/');
    }
}
