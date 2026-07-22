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

       /**
        * @return Commande[] retourne les commandes filtrées par statut et/ou nom de client
        */

       public function findFiltrees(?string $statut, ?string $client): array
       {
            $qb = $this->createQueryBuilder('c')
                ->leftJoin('c.utilisateur', 'u')
                ->leftJoin('c.statutHistoriques', 's')
                ->orderBy('c.dateCommande', 'DESC');

            if ($statut) {
                $qb->andWhere('s.statut = :statut')
                   ->setParameter('statut', $statut);
            }
            if ($client) {
                $qb->andWhere('u.nom LIKE :client OR u.prenom LIKE :client')
                   ->setParameter('client', '%' . $client . '%');
            }

            return $qb->getQuery()->getResult();
       }
}
