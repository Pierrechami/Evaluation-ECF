<?php
declare(strict_types=1);

namespace App\Tests\Instructeur;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class InstructeurRegistrationControllerTest extends WebTestCase
{
    private readonly UserRepository $userRepository;

    public function testRegistration(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('register_instructeur'));

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', "S'inscrire en tant qu'instructeur !");

        $form = $crawler->selectButton("S'inscrire")->form();

        $form['instructeur_registration_form_type[email]'] = 'test@example.com';
        $form['instructeur_registration_form_type[plainPassword]'] = 'Password123';
        $form['instructeur_registration_form_type[firstName]'] = 'John';
        $form['instructeur_registration_form_type[name]'] = 'Doe';
        $form['instructeur_registration_form_type[descriptionSpecialty]'] = 'Some description';
        $form['instructeur_registration_form_type[profilePicture]'] = 'portrait-instructeur.png';

        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();

        $user = $this->userRepository->findOneBy(['email' => 'test@example.com']);

        $this->assertNotNull($user);
        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getName());
        $this->assertEquals('Some description', $user->getDescriptionSpecialty());
        $this->assertNotNull($user->getProfilePicture());
    }

}