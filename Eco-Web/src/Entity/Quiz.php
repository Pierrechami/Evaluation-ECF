<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="quizzes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $section;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="quizzes")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $response1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $not_good1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $response2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $not_good2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question3;

    /**
     * @ORM\Column(type="boolean")
     */
    private $response3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $not_good3;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuestion1(): ?string
    {
        return $this->question1;
    }

    public function setQuestion1(string $question1): self
    {
        $this->question1 = $question1;

        return $this;
    }

    public function getResponse1(): ?bool
    {
        return $this->response1;
    }

    public function setResponse1( $response1): self
    {
        $this->response1 = $response1;

        return $this;
    }

    public function getNotGood1(): ?string
    {
        return $this->not_good1;
    }

    public function setNotGood1(?string $not_good1): self
    {
        $this->not_good1 = $not_good1;

        return $this;
    }

    public function getQuestion2(): ?string
    {
        return $this->question2;
    }

    public function setQuestion2(string $question2): self
    {
        $this->question2 = $question2;

        return $this;
    }

    public function getResponse2(): ?bool
    {
        return $this->response2;
    }

    public function setResponse2( $response2): self
    {
        $this->response2 = $response2;

        return $this;
    }

    public function getNotGood2(): ?string
    {
        return $this->not_good2;
    }

    public function setNotGood2(?string $not_good2): self
    {
        $this->not_good2 = $not_good2;

        return $this;
    }

    public function getQuestion3(): ?string
    {
        return $this->question3;
    }

    public function setQuestion3(string $question3): self
    {
        $this->question3 = $question3;

        return $this;
    }

    public function getResponse3(): ?bool
    {
        return $this->response3;
    }

    public function setResponse3( $response3): self
    {
        $this->response3 = $response3;

        return $this;
    }

    public function getNotGood3(): ?string
    {
        return $this->not_good3;
    }

    public function setNotGood3(?string $not_good3): self
    {
        $this->not_good3 = $not_good3;

        return $this;
    }
}
