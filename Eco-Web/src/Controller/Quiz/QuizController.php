<?php

namespace App\Controller\Quiz;

use App\Entity\Quiz;
use App\Form\QuizApprenantType;
use App\Form\QuizType;
use App\Repository\FormationRepository;
use App\Repository\QuizRepository;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quiz")
 */
class QuizController extends AbstractController
{
    /**
     * @Route("/show/{id}/response/apprenant/{app}", name="app_quiz_index", methods={"GET", "POST"})
     */
    public function index(QuizRepository $quizRepository, $id, $app): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app');
        }


        $quizInstructeur = $quizRepository->findOneBy(['section' => ['id' => $id], 'user' => null]);

        $quizApprenant = $quizRepository->findOneBy(['section' => ['id' => $id], 'user' => ['id' => $app]]);


        return $this->render('quiz/index.html.twig', [
            'quizInstructeur' => $quizInstructeur,
            'quizApprenant' => $quizApprenant
        ]);
    }

    # Création d'une question par un instructeur

    /**
     * @Route("/new/{id}", name="app_quiz_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, $id, SectionRepository $sectionRepository, FormationRepository $formationRepository, QuizRepository $quizRepository): Response
    {

        if ($this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']) {
            return $this->redirectToRoute('app');
        }

        $idUser = $this->getUser()->getId();
        $section = $sectionRepository->findOneBy(['id' => $id]);
        $idFormation = $section->getFormation()->getId();

        if ($formationRepository->findOneBy(['id' => $idFormation])->getUser()->getId() !== $idUser) {
            return $this->redirectToRoute('app');
        }

        if ($quizRepository->findOneBy(['section' => ['id' => $id]]) !== null) {
            $this->addFlash('questionnaire', 'Vous avez déjà déposé un questionnaire concernant cette section. Pour la modifier, la supprimer, ou même ajouter un nouveau questionnaire, contacter nos services.');
            return $this->redirectToRoute('liste_lesson_instructeur', ['id' => $section->getId()]);
        }


        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        $quiz->setSection($section);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_quiz_show", methods={"GET"})
     */
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }


    # Quand l'apprenant repond au question

    /**
     * @Route("/{id}/response/apprenant", name="app_quiz_edit", methods={"GET", "POST"})
     */
    public function ResponseApprenant(Request $request, EntityManagerInterface $entityManager, $id, QuizRepository $quizRepository): Response
    {
        if ($quizRepository->findBy(['section' => ['id' => $id]]) == []) {
            $this->addFlash('questionnaire-null', 'Il n\'y a pas de quiz pour le moment, mais revient vite, l\'instructeur est sûrement en train de l\'écrire.');
            return $this->redirectToRoute('liste_lesson', ['id' => $id]);
        }

        if ($quizRepository->findBy(['section' => ['id' => $id], 'user' => ['id' => $this->getUser()->getId()]]) !== []) {
            return $this->redirectToRoute('app_quiz_index', ['id' => $id, 'app' => $this->getUser()->getId()]);
        }


        $quiz = $quizRepository->findOneBy(['section' => ['id' => $id]]);

        $question1 = $quiz->getQuestion1();
        $question2 = $quiz->getQuestion2();
        $question3 = $quiz->getQuestion3();


        $responseQuiz = new Quiz();
        $form = $this->createForm(QuizApprenantType::class, $responseQuiz);
        $form->handleRequest($request);
        $responseQuiz->setSection($quiz->getSection());
        $responseQuiz->setUser($this->getUser());
        $responseQuiz->setQuestion1($question1);
        $responseQuiz->setQuestion2($question2);
        $responseQuiz->setQuestion3($question3);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($responseQuiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index', ['id' => $id, 'app' => $this->getUser()->getId()]);
        }

        return $this->renderForm('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
            'q1' => $question1,
            'q2' => $question2,
            'q3' => $question3
        ]);
    }





    /*
     *
     *  * @Route("/{id}/edit", name="app_quiz_edit", methods={"GET", "POST"})
        public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

     */

    /**
     * @Route("/{id}", name="app_quiz_delete", methods={"POST"})
     */
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quiz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
    }
}
