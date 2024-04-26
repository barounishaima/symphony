<?php

namespace App\Test\Controller;

use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EtudiantControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EtudiantRepository $repository;
    private string $path = '/etudiant/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Etudiant::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etudiant index');

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
            'etudiant[etudiant_id]' => 'Testing',
            'etudiant[nome]' => 'Testing',
            'etudiant[instit]' => 'Testing',
        ]);

        self::assertResponseRedirects('/etudiant/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etudiant();
        $fixture->setEtudiant_id('My Title');
        $fixture->setNome('My Title');
        $fixture->setInstit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etudiant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etudiant();
        $fixture->setEtudiant_id('My Title');
        $fixture->setNome('My Title');
        $fixture->setInstit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'etudiant[etudiant_id]' => 'Something New',
            'etudiant[nome]' => 'Something New',
            'etudiant[instit]' => 'Something New',
        ]);

        self::assertResponseRedirects('/etudiant/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEtudiant_id());
        self::assertSame('Something New', $fixture[0]->getNome());
        self::assertSame('Something New', $fixture[0]->getInstit());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Etudiant();
        $fixture->setEtudiant_id('My Title');
        $fixture->setNome('My Title');
        $fixture->setInstit('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/etudiant/');
    }
}
