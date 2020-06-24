<?php

namespace App\Repository;


use App\Entity\Student;
use Doctrine\ORM\EntityRepository;

class GradeRepository extends EntityRepository
{


    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function average()
    {
        $qb = $this->createQueryBuilder('g');
        return $qb->select($qb->expr()->avg('g.mark'));

    }


    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function retrieveAllAverage()
    {
        return $this->average()
            ->getQuery()
            ->getSingleScalarResult();

    }

    /**
     * @param Student $student
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function retrieveStudentAverage(Student $student)
    {
        return $this->average()
            ->where('g.student = :student')
            ->setParameter('student', $student)
            ->getQuery()
            ->getSingleScalarResult();

    }

}