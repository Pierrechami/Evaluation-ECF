<?php

namespace App\Tests\Controller\Formation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class FormationControllerTest extends WebTestCase
{

    public function testNew(): void
    {
        $client = static::createClient();

        $session = $client->getContainer()->get('session');
        $session->save();

        $crawler = $client->request('GET', '/formation/new');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('submit')->form();
        $form['formation[title]'] = 'Test Title';
        $form['formation[teaser_text]'] = 'Test description';
        $form['formation[picture]']->upload(new UploadedFile(
            '/Images/imageformation.jpg',
            'imageformation.jpg',
            'image/jpeg'
        ));

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/section/new'));
    }


}
