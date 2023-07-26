<?php

namespace App\Tests\Controller\HomePage;

use App\Entity\Comment;
use App\Entity\Formation;
use App\Entity\User;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageControllerTest extends WebTestCase
{

    public function testSomething(): void
    {
        $formationRepository = $this->createMock(FormationRepository::class);
        $user = (new User())
            ->setFirstname('John Doe')
            ->setEmail('exemple@exemple.com')
            ->setPassword('Exemple199*1')
            ->setRoles(['ROLE_APPRENANT']);
        $formations = [
            (new Formation())->setTitle('First Formation')->setUser($user),
            (new Formation())->setTitle('Second Formation')->setUser($user),
            (new Formation())->setTitle('Third Formation')->setUser($user),
        ];

        $formationRepository->expects($this->once())
            ->method('findBy')
            ->with([], ['id' => 'desc'], 3)
            ->willReturn($formations);

        $client = self::createClient();
        $client->getContainer()->set(FormationRepository::class, $formationRepository);

        $crawler = $client->request('GET', '/');

        // Assert that the response status code is 200 (HTTP_OK)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }


}
