<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

       /**
        * @return Commande[]
        */
       public function findByUtilisateur(Utilisateur $utilisateur): array
       {
           return $this->createQueryBuilder('c')
               ->andWhere('c.utilisateur = :utilisateur')
               ->setParameter('utilisateur', $utilisateur)
               ->orderBy('c.dateCommande', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }
}
