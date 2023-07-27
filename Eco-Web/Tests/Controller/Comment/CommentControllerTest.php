<?php
declare(strict_types=1);

namespace App\Tests\Controller\Comment;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CommentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CommentRepository $commentRepository;
    private string $path = '/comment/';

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->commentRepository = self::getContainer()->get('doctrine')->getRespository(Comment::class);

        foreach ($this->commentRepository->findAll() as $object) {
            $this->commentRepository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawel = $this->client->request('GET', '/admin' . $this->path);
        self::assertResponseStatusCodeSame(200);
    }


}