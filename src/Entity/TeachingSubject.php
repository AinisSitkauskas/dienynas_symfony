<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeachingSubjectRepository")
 */
class TeachingSubject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $teachingSubject;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Mark", mappedBy="teachingSubject")
     */
    private $marks;

    public function __construct()
    {
        $this->marks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeachingSubject(): ?string
    {
        return $this->teachingSubject;
    }

    public function setTeachingSubject(string $teachingSubject): self
    {
        $this->teachingSubject = $teachingSubject;

        return $this;
    }

    /**
     * @return Collection|Mark[]
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks[] = $mark;
            $mark->setTeachingSubject($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->contains($mark)) {
            $this->marks->removeElement($mark);
            // set the owning side to null (unless already changed)
            if ($mark->getTeachingSubject() === $this) {
                $mark->setTeachingSubject(null);
            }
        }

        return $this;
    }
}
