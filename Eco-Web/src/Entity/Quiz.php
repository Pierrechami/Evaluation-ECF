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
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $response1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $response2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $good_response;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="quizzes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $section;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getResponse1(): ?string
    {
        return $this->response1;
    }

    public function setResponse1(string $response1): self
    {
        $this->response1 = $response1;

        return $this;
    }

    public function getResponse2(): ?string
    {
        return $this->response2;
    }

    public function setResponse2(string $response2): self
    {
        $this->response2 = $response2;

        return $this;
    }

    public function getGoodResponse(): ?string
    {
        return $this->good_response;
    }

    public function setGoodResponse(string $good_response): self
    {
        $this->good_response = $good_response;

        return $this;
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
}
