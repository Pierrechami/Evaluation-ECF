<?php
declare(strict_types=1);

namespace App\Tests\Controller\Register;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{

    public function testRegistration()
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('register_apprenant'));

        // Vérifier que la page a répondu avec un statut HTTP 200
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Sélectionnez le formulaire et remplissez-le avec des données
        $form = $crawler->filter('form[name=apprenant_registration_form]')->form([
            'apprenant_registration_form[email]' => 'pierrechaminade@test.com',
            'apprenant_registration_form[password][first]' => 'Toooooto17',
            'apprenant_registration_form[password][second]' => 'Toooooto17',
            'apprenant_registration_form[pseudo]' => 'Pierro',
        ]);

        $client->submit($form);
        $client->followRedirect();

        $this->assertTrue($client->getResponse()->isRedirect('/login'));
        $this->assertTrue($client->getResponse()->isSuccessful());

        self::assertSelectorTextContains('.alert-success', "Félicitations ! Pour terminer ton inscription en tant qu'apprenant, clique sur le lien reçu par e-mail. Ensuite, tu pourras te connecter.");

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'pierrechaminade@test.com']);
        $this->assertNotNull($user);
        $this->assertEquals('Pierro', $user->getPseudo());
        $this->assertEquals(['ROLE_APPRENANT'], $user->getRoles());

        $entityManager->remove($user);
        $entityManager->flush();
    }
}