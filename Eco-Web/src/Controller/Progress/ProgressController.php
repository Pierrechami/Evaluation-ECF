<?php

namespace App\Controller\Progress;

use App\Entity\Progress;
use App\Form\ProgressType;
use App\Repository\ProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/progress')]
class ProgressController extends AbstractController
{
    #[Route(path: '/', name: 'app_progress_index', methods: ['GET'])]
    public function index(ProgressRepository $progressRepository): Response
    {
        return $this->render('progress/index.html.twig', [
            'progress' => $progressRepository->findAll(),
        ]);
    }

    #[Route(path: '/new', name: 'app_progress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgressRepository $progressRepository): Response
    {
        $progress = new Progress();
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progressRepository->add($progress);
            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('progress/new.html.twig', [
            'progress' => $progress,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'app_progress_show', methods: ['GET'])]
    public function show(Progress $progress): Response
    {
        return $this->render('progress/show.html.twig', [
            'progress' => $progress,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'app_progress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Progress $progress, ProgressRepository $progressRepository): Response
    {
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progressRepository->add($progress);
            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('progress/edit.html.twig', [
            'progress' => $progress,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'app_progress_delete', methods: ['POST'])]
    public function delete(Request $request, Progress $progress, ProgressRepository $progressRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$progress->getId(), $request->request->get('_token'))) {
            $progressRepository->remove($progress);
        }

        return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
    }
}
