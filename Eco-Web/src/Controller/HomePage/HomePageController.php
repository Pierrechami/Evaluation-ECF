<?php

namespace App\Controller\HomePage;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class HomePageController extends AbstractController
{
    #[Route(path: '/', name: 'app')]
    public function index(FormationRepository $formationRepository, UserInterface $user): Response
    {
        $formations = $formationRepository->findBy([], ['id' => 'desc'], 3);
        return $this->render('home_page/home_page.html.twig', [
            'formations' => $formations
        ]);

    }
}
