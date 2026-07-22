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
        * @return Menu[] Retourne les menus actifs
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

       // Filtres optionnels : la condition n'est ajoutée que si le paramètre est donné
       public function findFiltres(?string $prixMax, ?string $themeId, ?string $regimeId, ?string $nbPersonnes): array
       {
            $qb = $this->createQueryBuilder('m')
                ->andWhere('m.actif = :actif')
                ->setParameter('actif', true);

            if($prixMax){
                $qb->andWhere('m.prixBase <= :prixMax')
                   ->setParameter('prixMax', $prixMax);
            }

            if($themeId){
                $qb->andWhere('m.theme = :themeId')
                   ->setParameter('themeId', $themeId);
            }
            if($regimeId){
                $qb->andWhere('m.regime = :regimeId')
                   ->setParameter('regimeId', $regimeId);
            }

            if($nbPersonnes){
                $qb->andWhere('m.nbPersonnesMin >= :nbPersonnes')
                   ->setParameter('nbPersonnes', $nbPersonnes);
            }

            return $qb->orderBy('m.titre','ASC')
                ->getQuery()
                ->getResult();
       }
}
