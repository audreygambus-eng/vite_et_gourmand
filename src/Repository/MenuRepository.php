<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

       /**
        * @return Menu[] Returns an array of Menu objects
        */
       public function findActifs(): array
       {
           return $this->createQueryBuilder('m')
               ->andWhere('m.actif = :actif')
               ->setParameter('actif', true)
               ->orderBy('m.titre', 'ASC')
               ->getQuery()
               ->getResult()
           ;
       }
}
