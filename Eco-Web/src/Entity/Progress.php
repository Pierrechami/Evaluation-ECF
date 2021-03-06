<?php

namespace App\Entity;

use App\Repository\ProgressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgressRepository::class)
 */
class Progress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $lesson_finished;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $formation_finished;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="progress")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Lesson::class, inversedBy="progress")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lesson;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="progress")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $formation_progress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLessonFinished(): ?bool
    {
        return $this->lesson_finished;
    }

    public function setLessonFinished(?bool $lesson_finished): self
    {
        $this->lesson_finished = $lesson_finished;

        return $this;
    }

    public function getFormationFinished(): ?bool
    {
        return $this->formation_finished;
    }

    public function setFormationFinished(?bool $formation_finished): self
    {
        $this->formation_finished = $formation_finished;

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

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getFormationProgress(): ?int
    {
        return $this->formation_progress;
    }

    public function setFormationProgress(?int $formation_progress): self
    {
        $this->formation_progress = $formation_progress;

        return $this;
    }
}
