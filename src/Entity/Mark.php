<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarkRepository")
 */
class Mark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="marks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkStudent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TeachingSubject", inversedBy="marks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkTeachingSubject;

    /**
     * @ORM\Column(type="integer")
     */
    private $mark;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?User
    {
        return $this->fkStudent;
    }

    public function setStudent(?User $fkStudent): self
    {
        $this->fkStudent = $fkStudent;

        return $this;
    }

    public function getTeachingSubject(): ?TeachingSubject
    {
        return $this->fkTeachingSubject;
    }

    public function setTeachingSubject(?TeachingSubject $fkTeachingSubject): self
    {
        $this->fkTeachingSubject = $fkTeachingSubject;

        return $this;
    }

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(int $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
