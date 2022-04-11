<?php

namespace App\Controller\Formation;

use App\Entity\Formation;
use App\Entity\Progress;
use App\Form\FormationType;
use App\Form\ProgressType;
use App\Repository\FormationRepository;
use App\Repository\ProgressRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{
    # Tous le monde peut visualisé les formations mais pas les modifier

    /**
     * @Route("/", name="app_formation_index", methods={"GET"})
     */
    public function index(FormationRepository $formationRepository, ProgressRepository $progressRepository): Response
    {
        if ($this->getUser() !== null){

       $formationEnCours = $progressRepository->findBy(['user' => ['id' => $this->getUser()->getId()] , 'formation_progress' => null , 'formation_finished' => null]);
       $formationTerminee = $progressRepository->findBy(['user' => ['id' => $this->getUser()->getId()], 'formation_finished' => true]);

            return $this->render('formation/index.html.twig', [
                'formations' => $formationRepository->findAll(),
                'formationEnCours' => $formationEnCours,
                'formationTerminees' => $formationTerminee
            ]);

        }

        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),

        ]);
    }

    # L'instructeur peut visualiser  et modifier uniquement ses formation

    /**
     * @Route("/liste", name="liste_formations", methods={"GET"})
     */
    public function liste(FormationRepository $formationRepository): Response
    {
        if( $this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']){
            return $this->redirectToRoute('app');
        }

       $formationsInstructeur =  $formationRepository->findBy(['user' => $this->getUser()]);
        return $this->render('formation/Instructeur/liste.html.twig', [
            'formations' => $formationsInstructeur
        ]);
    }



    /**
     * @Route("/new", name="app_formation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FormationRepository $formationRepository): Response
    {
        if($this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']){
            return $this->redirectToRoute('app');
        }

       $thisUser = $this->getUser();

        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $formation->setUser($thisUser);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $formationPicture */
            $formationPicture = $form->get('picture')->getData();

            if ($formationPicture) {
                $newFilename = uniqid().'.'.$formationPicture->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $formationPicture->move(
                        $this->getParameter('kernel.project_dir') . '/public/Formation',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error' , 'Vous n\avez pas rempli le formulaire correctement.  ');
                }


                $formation->setPicture($newFilename);
            }
            $formationRepository->add($formation);


            return $this->redirectToRoute('app_section_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_formation_show", methods={"GET", "POST"})
     */
    public function show(Formation $formation , SectionRepository $sectionRepository , $id, ProgressRepository $progressRepository): Response
    {
        $sectionFormation = $sectionRepository->findBy(['formation' => ['id'=> $id]]);

        if ($this->getUser() == null){
            $this->addFlash('obligation-apprenant', 'Vous devez vous créer un compte pour pouvoir accéder aux formations.');
            return $this->redirectToRoute('register_apprenant');
        }

        # Récupérer l'id de la formation
        $idformation = $formation->getId();
        #  Récupère toutes les section de la formation
        $sectionFormation = $sectionRepository->findBy(['formation' => ['id' => $idformation]]);
        $lessons = [];
        # Pour chaque section, récupère les leçon
        foreach ($sectionFormation as $sections) {
            $lessons [] = $sections->getLessons()->getValues();
        }

        # Pour chaque section, récupère le nombre de leçons
        $nombrelesson = 0;
        foreach ($lessons as $value) {
            $nombrelesson += count($value);
        }

        # Récupère toute les ligne du tableau de l'user avec la formation
        $Userlessons = $progressRepository->findBy(['user' => $this->getUser(), 'formation' => ['id' => $idformation], 'formation_finished' => null]);

        if ($nombrelesson == 0){
            $pourcentageFormation = 0;
        }else{
            $pourcentageFormation = 100 * count($Userlessons) / $nombrelesson ;
            $pourcentageFormation = round($pourcentageFormation);
        }


        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'sectionsFormation' => $sectionFormation,
            'pourcentageFormation' => $pourcentageFormation
        ]);
    }

    /**
     * @Route("/{id}/instructeur", name="formation_show_instructeur", methods={"GET"})
     */
    public function showInstructeur(Formation $formation , SectionRepository $sectionRepository , $id): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app');
        }

        $idInstructeur = $this->getUser()->getId();
        $idInstructeurFormation = $formation->getUser()->getId();


        if($this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR'] || $idInstructeur !== $idInstructeurFormation){
            return $this->redirectToRoute('app');
        }

        $sectionFormation = $sectionRepository->findBy(['formation' => ['id'=> $id]]);

        if ($this->getUser() == null){
            $this->addFlash('obligation-apprenant', 'Vous devez vous créer un compte pour pouvoir accéder aux formations.');
            return $this->redirectToRoute('register_apprenant');
        }
        return $this->render('formation/Instructeur/section.html.twig', [
            'formation' => $formation,
            'sectionsFormation' => $sectionFormation
        ]);
    }



    /**
     * @Route("/{id}/edit", name="app_formation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Formation $formation, FormationRepository $formationRepository, $id): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app');
        }

        $idInstructeur = $this->getUser()->getId();
        $idInstructeurFormation = $formation->getUser()->getId();

        if($this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR'] || $idInstructeur !== $idInstructeurFormation){
            return $this->redirectToRoute('app');
        }

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formationRepository->add($formation);
            return $this->redirectToRoute('liste_formations', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_formation_delete", methods={"POST"})
     */
    public function delete(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {

        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $formationRepository->remove($formation);
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
