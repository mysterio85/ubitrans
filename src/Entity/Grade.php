<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Student;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GradeRepository")
 * @ORM\Table(name="grade")
 */
class Grade
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $subject;

    /**
     * @ORM\Column(type="float")
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 20,
     *      notInRangeMessage = "The mark must be between {{ min }} and {{ max }} ",
     * )
     */
    private $mark;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class)
     */
    protected $student;


    public function getId(): int
    {
        return $this->id;
    }


    public function getSubject(): string
    {
        return $this->subject;
    }


    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }


    public function getMark(): float
    {
        return $this->mark;
    }

    public function setMark(float $mark): self
    {
        $this->mark = $mark;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStudent(): Student
    {
        return $this->student;
    }


    public function setStudent(Student $student): self
    {
        $this->student = $student;
        return $this;
    }


}