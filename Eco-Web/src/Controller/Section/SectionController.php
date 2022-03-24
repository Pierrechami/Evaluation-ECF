<?php

namespace App\Controller\Section;

use App\Entity\Section;
use App\Form\FormationSection;
use App\Form\SectionType;
use App\Repository\FormationRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formation-section")
 */
class SectionController extends AbstractController
{
    /**
     * @Route("/", name="app_section_index", methods={"GET"})
     */
    public function index(SectionRepository $sectionRepository): Response
    {
        return $this->render('section/index.html.twig', [
            'sections' => $sectionRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="app_section_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SectionRepository $sectionRepository, FormationRepository $formationRepository): Response
    {

         $lastformationUser =  $formationRepository->findBy(['user' => $this->getUser()], ['id' => 'desc'], 1)[0];
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        $section->setFormation($lastformationUser);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/new.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    // Ajout d'une nouvelle section sans passer par la case nouvelle formation
    /**
     * @Route("/new/section", name="new_section", methods={"GET", "POST"})
     */
    public function newSection(Request $request, SectionRepository $sectionRepository, FormationRepository $formationRepository): Response
    {

        $formationUser =  $formationRepository->findBy(['user' => $this->getUser()]);
        $section = new Section();
        $form = $this->createForm(FormationSection::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/new_formation_section.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }



    /**
     * @Route("/{id}", name="app_section_show", methods={"GET"})
     */
    public function show(Section $section): Response
    {
        return $this->render('section/show.html.twig', [
            'section' => $section,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_section_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Section $section, SectionRepository $sectionRepository): Response
    {
        $form = $this->createForm(FormationSection::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_section_delete", methods={"POST"})
     */
    public function delete(Request $request, Section $section, SectionRepository $sectionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $sectionRepository->remove($section);
        }

        return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
    }
}
