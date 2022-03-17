<?php

namespace App\Controller\RegistrationInstructeur;

use App\Entity\User;
use App\Form\InstructeurRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/instructeur", name="register_instructeur")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(InstructeurRegistrationFormType::class, $user);
        $form->handleRequest($request);
        $user->setIsAccepted(false);
        $user->setRoles(['ROLE_INSTRUCTEUR']);

        if ($form->isSubmitted() && $form->isValid()) {
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
                $newFilename = uniqid().'.'.$photoProfil->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoProfil->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error' , $e->getMessage());
                }


                $user->setProfilePicture($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app');
        }

        return $this->render('registrationInstructeur/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
