<?php

namespace App\Controller\RegistrationApprenants;

use App\Entity\User;
use App\Form\ApprenantRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/apprenant", name="register_apprenant")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(ApprenantRegistrationFormType::class, $user);
        $form->handleRequest($request);




        $user->setIsAccepted(true);
        $user->setRoles(['ROLE_APPRENANT']);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getEmail('chaminadepierre.24@gmail.com')){
                $user->setRoles(['ROLE_ADMIN']);
            }
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $this->addFlash('inscription-apprenant', 'Félicitations, tu viens de t\'inscrire en tant qu\'apprenant. Connecte-toi !  ');

            return $this->redirectToRoute('login');
        }

        return $this->render('registrationApprenant/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
