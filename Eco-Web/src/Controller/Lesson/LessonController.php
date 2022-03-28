<?php

namespace App\Controller\Lesson;

use App\Entity\Lesson;
use App\Form\LessonType;
use App\Repository\FormationRepository;
use App\Repository\LessonRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lesson")
 */
class LessonController extends AbstractController
{
    /**
     * @Route("/", name="app_lesson_index", methods={"GET"})
     */
    public function index(LessonRepository $lessonRepository): Response
    {
        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_lesson_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LessonRepository $lessonRepository, SectionRepository $sectionRepository, FormationRepository $formationRepository): Response
    {
        if ($this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']) {
            return $this->redirectToRoute('app');
        }

        $lastformationUser = $formationRepository->findBy(['user' => $this->getUser()], ['id' => 'desc'], 1)[0]->getId();
        $lastSection = $sectionRepository->findBy(['formation' => ['id' => $lastformationUser]])[0];


        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);
        $lesson->setSection($lastSection);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $picture1 */
            $picture1 = $form->get('picture1')->getData();

            if ($picture1) {
                $newFilename = uniqid().'.'.$picture1->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $picture1->move(
                        $this->getParameter('kernel.project_dir') . '/public/Lesson',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error' , 'Vous n\avez pas rempli le formulaire correctement.  ');
                }


                $lesson->setPicture1($newFilename);
            }

            /** @var UploadedFile $picture2 */
            $picture2 = $form->get('picture2')->getData();

            if ($picture2) {
                $newFilename2 = uniqid().'.'.$picture2->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $picture2->move(
                        $this->getParameter('kernel.project_dir') . '/public/Lesson',
                        $newFilename2
                    );
                } catch (FileException $e) {
                    $this->addFlash('error' , 'Vous n\avez pas rempli le formulaire correctement.  ');
                }


                $lesson->setPicture2($newFilename2);
            }

            /** @var UploadedFile $picture3 */
            $picture3 = $form->get('picture3')->getData();

            if ($picture3) {
                $newFilename3 = uniqid().'.'.$picture3->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $picture3->move(
                        $this->getParameter('kernel.project_dir') . '/public/Lesson',
                        $newFilename3
                    );
                } catch (FileException $e) {
                    $this->addFlash('error' , 'Vous n\avez pas rempli le formulaire correctement.  ');
                }


                $lesson->setPicture3($newFilename3);
            }




            $lessonRepository->add($lesson);
            $this->addFlash('formation-fini', 'Félicitations, vous venez de créer une nouvelle formation ! Vous pouvez ajouter de nouvelles sections ou leçons dans l\'onglet "Mes formations".');
            return $this->redirectToRoute('app', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
            'lastSection' => $lastSection
        ]);
    }

    # Création d'une leçon sans passer par la case "créer une formation"
    #  /**
    #    * @Route("/new", name="app_lesson_new", methods={"GET", "POST"})
    #    */
    #   public function new(Request $request, LessonRepository $lessonRepository): Response
    #   {
    #       $lesson = new Lesson();
    #       $form = $this->createForm(LessonType::class, $lesson);
    #      $form->handleRequest($request);
#
    #       if ($form->isSubmitted() && $form->isValid()) {
    #          $lessonRepository->add($lesson);
    #         return $this->redirectToRoute('app_lesson_index', [], Response::HTTP_SEE_OTHER);
    #    }

    #   return $this->renderForm('lesson/new.html.twig', [
    #      'lesson' => $lesson,
    #     'form' => $form,
    # ]);
    # }

    /**
     * @Route("/{id}", name="app_lesson_show", methods={"GET"})
     */
    public function show(Lesson $lesson): Response
    {
        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_lesson_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Lesson $lesson, LessonRepository $lessonRepository): Response
    {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lessonRepository->add($lesson);
            return $this->redirectToRoute('app_lesson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_lesson_delete", methods={"POST"})
     */
    public function delete(Request $request, Lesson $lesson, LessonRepository $lessonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $lesson->getId(), $request->request->get('_token'))) {
            $lessonRepository->remove($lesson);
        }

        return $this->redirectToRoute('app_lesson_index', [], Response::HTTP_SEE_OTHER);
    }
}
