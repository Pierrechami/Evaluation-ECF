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
     * @ORM\Column(type="boolean")
     */
    private $good;

    /**
     * @ORM\Column(type="boolean")
     */
    private $no_good;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $good_reponse;

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

    public function getGood(): ?bool
    {
        return $this->good;
    }

    public function setGood(bool $good): self
    {
        $this->good = $good;

        return $this;
    }

    public function getNoGood(): ?bool
    {
        return $this->no_good;
    }

    public function setNoGood(bool $no_good): self
    {
        $this->no_good = $no_good;

        return $this;
    }

    public function getGoodReponse(): ?string
    {
        return $this->good_reponse;
    }

    public function setGoodReponse(?string $good_reponse): self
    {
        $this->good_reponse = $good_reponse;

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
