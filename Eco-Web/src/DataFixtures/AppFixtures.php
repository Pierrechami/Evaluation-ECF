<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Formation;
use App\Entity\Lesson;
use App\Entity\Progress;
use App\Entity\Quiz;
use App\Entity\Section;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // admin
        $admin = new User();
        $admin->setEmail('');
        $admin->setPseudo('admin');
        $passwordAdmin = $this->hasher->hashPassword($admin, 'superAdmin');
        $admin->setPassword($passwordAdmin);
        $admin->setIsAccepted(true);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Instructeur non validé
        $instructeurN = new User();
        $instructeurN->setEmail('instructeurnonvalide@gmail.com');
        $instructeurN->setRoles(['ROLE_POSTULANT']);
        $passwordInstructeurN = $this->hasher->hashPassword($instructeurN, 'Instructeur');
        $instructeurN->setPassword($passwordInstructeurN);
        $instructeurN->setFirstName($faker->firstName());
        $instructeurN->setName($faker->name());
        $instructeurN->setProfilePicture('\Images\etudiant-pierre.png');
        $instructeurN->setDescriptionSpecialty($faker->words(3, true));
        $instructeurN->setIsAccepted(false);

        $manager->persist($instructeurN);

        // Instructeur validé
        $instructeur = new User();
        $instructeur->setEmail('instructeur@gmail.com');
        $instructeur->setRoles(['ROLE_INSTRUCTEUR']);
        $passwordInstructeur = $this->hasher->hashPassword($instructeur, 'Instructeur');
        $instructeur->setPassword($passwordInstructeur);
        $instructeur->setFirstName($faker->firstName());
        $instructeur->setName($faker->name());
        $instructeur->setProfilePicture('\Images\etudiant-pierre.png');
        $instructeur->setDescriptionSpecialty($faker->words(3, true));
        $instructeur->setIsAccepted(true);

        $manager->persist($instructeur);

        // Apprenant

        $apprenant = new User();
        $apprenant->setEmail('apprenant@gmail.com');
        $apprenant->setRoles(['ROLE_APPRENANT']);
        $passwordApprenant = $this->hasher->hashPassword($apprenant, 'Apprenant');
        $apprenant->setPassword($passwordApprenant);
        $apprenant->setPseudo($faker->firstName());
        $apprenant->setIsAccepted(true);

        $manager->persist($apprenant);

        for ($f = 0; $f <= 10; $f++) {
            $formation = new Formation();
            $formation->setUser($instructeur);
            $formation->setPicture('\formation.jpg');
            $formation->setTeaserText($faker->words(30, true));
            $formation->setTitle($faker->words(3, true));

            $manager->persist($formation);

            for ($s = 0; $s <= 5; $s++) {
                $section = new Section();
                $section->setFormation($formation);
                $section->setTitle($faker->words(3, true));

                $manager->persist($section);

                for ($l = 0; $l <= 10; $l++) {
                    $lesson = new Lesson();
                    $lesson->setTitle($faker->words(4, true));
                    $lesson->setSection($section);
                    $lesson->setContent($faker->text(6000));
                    $lesson->setPicture1('\photo1.png');
                    $lesson->setPicture2('\photo2.png');
                    $lesson->setPicture3('\photo3.png');
                    $lesson->setVideo('https://www.youtube.com/embed/oEAuNzWXRjM');

                    $manager->persist($lesson);

                    for ($c = 0; $c <= 10; $c++) {
                        $commentaire = new Comment();
                        $commentaire->setContent($faker->words(25, true));
                        $commentaire->setUser($apprenant);
                        $commentaire->setLesson($lesson);

                        $manager->persist($commentaire);
                    }

                    $quiz = new Quiz();
                    $quiz->setSection($section);
                    $quiz->setUser(null);
                    $quiz->setQuestion1($faker->words(10, true));
                    $quiz->setResponse1(true);
                    $quiz->setNotGood1(null);
                    $quiz->setQuestion2($faker->words(10, true));
                    $quiz->setResponse2(false);
                    $quiz->setNotGood2($faker->words(10, true));
                    $quiz->setQuestion3($faker->words(20, true));
                    $quiz->setResponse3(false);
                    $quiz->setNotGood3($faker->words(20, true));

                    $manager->persist($quiz);

                }
            }



        }

        $finish = new Progress();
        $finish->setUser($apprenant);
        $finish->setLesson($lesson);
        $finish->setFormation($formation);
        $finish->setLessonFinished(true);
        $finish->setFormationFinished(true);

        $manager->persist($finish);



        /////
        $manager->flush();
    }
}
