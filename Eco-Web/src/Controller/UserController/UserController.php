<?php

namespace App\Controller\UserController;

use App\Entity\User;
use App\Form\InstructeurRegistrationFormType;
use App\Form\ValidationInstructeurs;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{


    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        if ($this->getUser()->getRoles()[0] !== 'ROLE_ADMIN') {
            return $this->redirectToRoute('app');
        }

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()->getRoles()[0] !== 'ROLE_ADMIN') {
            return $this->redirectToRoute('app');
        }


        $user = new User();
        $form = $this->createForm(InstructeurRegistrationFormType::class, $user);
        $form->handleRequest($request);
        $user->setIsAccepted(true);
        $user->setRoles(['ROLE_INSTRUCTEUR']);


        if ($form->isSubmitted() && $form->isValid()) {
            #$userRepository->add($user);
            #return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            /** @var UploadedFile $photoProfil */
            $photoProfil = $form->get('profilePicture')->getData();

            if ($photoProfil) {
                $newFilename = uniqid() . '.' . $photoProfil->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoProfil->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Vous n\avez pas rempli le formulaire correctement.  ');
                }


                $user->setProfilePicture($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('nouveau-instructeur', 'Félicitations, vous venez de créer un nouveau profil instructeur ! ');

            return $this->redirectToRoute('app');

        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user, $id): Response
    {


        if ($this->getUser()->getid() == $id || $this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {

            return $this->render('user/show.html.twig', [
                'user' => $user,

            ]);
        }
        return $this->redirectToRoute('app');
    }

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, $id): Response
    {

        if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {

            $form = $this->createForm(ValidationInstructeurs::class, $user);
            $form->handleRequest($request);
            if ($user->getIsAccepted() == true) {
                $user->setRoles(['ROLE_INSTRUCTEUR']);
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $userRepository->add($user);
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }


            return $this->renderForm('user/edit.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        }
        return $this->redirectToRoute('app');
    }

    /**
     * @Route("/delete/{id}", name="app_user_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository, $id): Response
    {


        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }


        if ($this->getUser()->getid() == $id || $this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app');
    }
}
