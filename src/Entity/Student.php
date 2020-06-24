<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="student")
 */
class Student
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
    private $lastName;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @Assert\Date
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\OneToMany(targetEntity=Grade::class, cascade={"persist", "remove"}, mappedBy="student")
     */
    protected $grades;

    /**
     * Student constructor.
     */
    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    public function getGrades(): ArrayCollection
    {
        return $this->grades;
    }

    public function addGrades(Grade $grade)
    {
        $this->grades->add($grade);
        $grade->setStudent($this);
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getBirthDate(): \DateTime
    {
        return $this->birthDate;
    }


    public function setBirthDate(\DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;
        return $this;
    }


}