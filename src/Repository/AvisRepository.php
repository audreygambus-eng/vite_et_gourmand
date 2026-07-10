<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

       /**
        * @return Avis[] Returns an array of Avis objects (avis validés, antéchronologiques)
        */
       public function findValides(): array
       {
           return $this->createQueryBuilder('a')
               ->andWhere('a.valide = :valide')
               ->setParameter('valide', true)
               ->orderBy('a.dateCreation', 'DESC')
               ->setMaxResults(6)
               ->getQuery()
               ->getResult()
           ;
       }
}
