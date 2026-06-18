<?php

namespace App\Repository;

use App\Entity\Soutenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Soutenance>
 */
class SoutenanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soutenance::class);
    }
    public function findByEnseignant($enseignant): array
{
    return $this->createQueryBuilder('s')
        ->where('s.president = :e OR s.rapporteur = :e OR s.examinateur = :e')
        ->setParameter('e', $enseignant)
        ->orderBy('s.date', 'ASC')
        ->getQuery()
        ->getResult();
}
public function findByDate(\DateTime $date): array
{
    return $this->createQueryBuilder('s')
        ->where('s.date = :date')
        ->setParameter('date', $date)
        ->getQuery()
        ->getResult();
}

public function findConflict($salle, $date, $heure): ?Soutenance
{
    return $this->createQueryBuilder('s')
        ->where('s.salle = :salle')
        ->andWhere('s.date = :date')
        ->andWhere('s.heure = :heure')
        ->setParameter('salle', $salle)
        ->setParameter('date', $date)
        ->setParameter('heure', $heure)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}

public function findJuryConflict(array $enseignants, $date, $heure): ?Soutenance
{
    return $this->createQueryBuilder('s')
        ->where('s.president IN (:e) OR s.rapporteur IN (:e) OR s.examinateur IN (:e)')
        ->andWhere('s.date = :date')
        ->andWhere('s.heure = :heure')
        ->setParameter('e', $enseignants)
        ->setParameter('date', $date)
        ->setParameter('heure', $heure)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}

    //    /**
    //     * @return Soutenance[] Returns an array of Soutenance objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Soutenance
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
