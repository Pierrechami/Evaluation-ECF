<?php

namespace App\Controller\Section;

use App\Entity\Lesson;
use App\Entity\User;
use App\Entity\Section;
use App\Form\FormationSection;
use App\Form\SectionType;
use App\Repository\FormationRepository;
use App\Repository\LessonRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/formation-section')]
class SectionController extends AbstractController
{


        #liste des leçon par rapport a la section en cours pour les apprenant
    #[Route(path: '/{id}/liste/lesson', name: 'liste_lesson', methods: ['GET'])]
    public function listeLesson($id , SectionRepository $sectionRepository , LessonRepository $lessonRepository): Response
    {
        if( $this->getUser() == null){
            return $this->redirectToRoute('app');
        }



        $sectionEncour = $sectionRepository->findBy(['id' => $id])[0];
        $formationsectionId = $sectionEncour->getFormation()->getId();
        $lessons = $lessonRepository->findBy(['section' => ['id' => $sectionEncour->getId()]]);


        return $this->render('section/index.html.twig', [
            'sectionEncour' => $sectionEncour,
            'lessons' => $lessons,
            'formation' => $formationsectionId
        ]);
    }

    #[Route(path: '/{id}/liste/lesson/instructeur', name: 'liste_lesson_instructeur', methods: ['GET', 'POST'])]
    public function listeLessonInstructeur($id , SectionRepository $sectionRepository , LessonRepository $lessonRepository, FormationRepository $formationRepository): Response
    {
        if( $this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']){
            return $this->redirectToRoute('app');
        }

        $sectionEncour = $sectionRepository->findBy(['id' => $id])[0];
        $formationsectionId = $sectionEncour->getFormation()->getId();
        $lessons = $lessonRepository->findBy(['section' => ['id' => $sectionEncour->getId()]]);


        if ($formationRepository->findBy(['id' => $formationsectionId])[0]->getUser()->getId() !== $this->getUser()->getId()){
            return $this->redirectToRoute('app');
        }



        return $this->render('formation/instructeur/liste_lesson.html.twig', [
            'sectionEncour' => $sectionEncour,
            'lessons' => $lessons,
            'formation' => $formationsectionId
        ]);
    }


    #[Route(path: '/new', name: 'app_section_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SectionRepository $sectionRepository, FormationRepository $formationRepository, FormationSection $formationSection): Response
    {
        if($this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']){
            return $this->redirectToRoute('app');
        }

         $lastformationUser =  $formationRepository->findBy(['user' => $this->getUser()], ['id' => 'desc'], 1)[0];
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        $section->setFormation($lastformationUser);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('app_lesson_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/new.html.twig', [
            'section' => $section,
            'form' => $form,
            'lastformationUser' => $lastformationUser
        ]);
    }

    // Ajout d'une nouvelle section sans passer par la case nouvelle formation
    #[Route(path: '/new/section/{id}', name: 'new_section', methods: ['GET', 'POST'])]
    public function newSection($id , Request $request, SectionRepository $sectionRepository, FormationRepository $formationRepository): Response
    {

        if ($this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']){
            return $this->redirectToRoute('app');
        }

        $formation = $formationRepository->findBy(['id' => $id])[0];

        if($formation->getUser()->getId() !== $this->getUser()->getId()){
            return $this->redirectToRoute('app');
        }

        $section = new Section();
        $form = $this->createForm(FormationSection::class, $section);
        $form->handleRequest($request);
        $section->setFormation($formation);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('formation_show_instructeur' , ['id'=> $id]);
        }

        return $this->renderForm('section/new_formation_section.html.twig', [
            'section' => $section,
            'form' => $form,
            'formation'=>$formation
        ]);
    }




    #[Route(path: '/{id}/edit', name: 'app_section_edit', methods: ['GET', 'POST'])]
    public function edit( Request $request, Section $section, SectionRepository $sectionRepository, $id ): Response
    {
        $idsectionInstructeur = $section->getFormation()->getUser()->getId();

        $idFormation = $section->getFormation()->getId();

        if($this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR'] || $idsectionInstructeur !== $this->getUser()->getId() ){
            return $this->redirectToRoute('app');
        }

        $form = $this->createForm(FormationSection::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('formation_show_instructeur' , ['id'=> $idFormation]);
        }

        return $this->renderForm('section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
            'idformation' => $idFormation
        ]);
    }

    #[Route(path: '/{id}', name: 'app_section_delete', methods: ['POST'])]
    public function delete(Request $request, Section $section, SectionRepository $sectionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $sectionRepository->remove($section);
        }

        return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
    }
}
