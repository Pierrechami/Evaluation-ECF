<?php

namespace App\Tests\Formation;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

class FormationControllerTest extends PantherTestCase
{

    public function testNew(): void
    {
        // Créez un client HTTP pour interagir avec votre application
        $client = static::createClient();

        $session = $client->getContainer()->get('session');
        $session->save();

        // Naviguer vers la page 'new'
        $crawler = $client->request('GET', '/formation/new');

        // Vérifier que la page a répondu avec un statut HTTP 200
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Sélectionnez le formulaire et remplissez-le avec des données
        $form = $crawler->selectButton('submit')->form();
        $form['formation[title]'] = 'Test Title';
        $form['formation[teaser_text]'] = 'Test description';
        $form['formation[picture]']->upload(new UploadedFile(
            '/Images/imageformation.jpg', // Path to the test image
            'imageformation.jpg',
            'image/jpeg'
        ));

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/section/new'));

    }


}
