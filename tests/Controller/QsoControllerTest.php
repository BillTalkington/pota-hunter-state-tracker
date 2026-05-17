<?php

namespace App\Tests\Controller;

use App\Entity\Qso;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class QsoControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    /** @var EntityRepository<Qso> */
    private EntityRepository $qsoRepository;
    private string $path = '/qso/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->qsoRepository = $this->manager->getRepository(Qso::class);

        foreach ($this->qsoRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Qso index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'qso[activatorCallsign]' => 'Testing',
            'qso[parkReference]' => 'Testing',
            'qso[state]' => 'Testing',
            'qso[band]' => 'Testing',
            'qso[mode]' => 'Testing',
            'qso[contactedAt]' => 'Testing',
        ]);

        self::assertResponseRedirects('/qso');

        self::assertSame(1, $this->qsoRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }

    public function testShow(): void
    {
        $fixture = new Qso();
        $fixture->setActivatorCallsign('My Title');
        $fixture->setParkReference('My Title');
        $fixture->setState('My Title');
        $fixture->setBand('My Title');
        $fixture->setMode('My Title');
        $fixture->setContactedAt('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Qso');

        // Use assertions to check that the properties are properly displayed.
        $this->markTestIncomplete('This test was generated');
    }

    public function testEdit(): void
    {
        $fixture = new Qso();
        $fixture->setActivatorCallsign('Value');
        $fixture->setParkReference('Value');
        $fixture->setState('Value');
        $fixture->setBand('Value');
        $fixture->setMode('Value');
        $fixture->setContactedAt('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'qso[activatorCallsign]' => 'Something New',
            'qso[parkReference]' => 'Something New',
            'qso[state]' => 'Something New',
            'qso[band]' => 'Something New',
            'qso[mode]' => 'Something New',
            'qso[contactedAt]' => 'Something New',
        ]);

        self::assertResponseRedirects('/qso');

        $fixture = $this->qsoRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getActivatorCallsign());
        self::assertSame('Something New', $fixture[0]->getParkReference());
        self::assertSame('Something New', $fixture[0]->getState());
        self::assertSame('Something New', $fixture[0]->getBand());
        self::assertSame('Something New', $fixture[0]->getMode());
        self::assertSame('Something New', $fixture[0]->getContactedAt());

        $this->markTestIncomplete('This test was generated');
    }

    public function testRemove(): void
    {
        $fixture = new Qso();
        $fixture->setActivatorCallsign('Value');
        $fixture->setParkReference('Value');
        $fixture->setState('Value');
        $fixture->setBand('Value');
        $fixture->setMode('Value');
        $fixture->setContactedAt('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/qso');
        self::assertSame(0, $this->qsoRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }
}
