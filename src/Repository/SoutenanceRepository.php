<?php

namespace App\Repository;

use App\Entity\Soutenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\ArrayParameterType;

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
        $dateStr = $date->format('Y-m-d');
        return $this->createQueryBuilder('s')
            ->where("s.date = :date")
            ->setParameter('date', $dateStr)
            ->getQuery()
            ->getResult();
    }

public function findConflict($salle, $date, $heure, ?int $excludeId = null): ?Soutenance
{
    $conn = $this->getEntityManager()->getConnection();
    $dateStr = $date->format('Y-m-d');
    $heureStr = $heure->format('H:i:s');

    $sql = 'SELECT id FROM soutenance WHERE salle_id = :salle AND date = :date AND heure = :heure';
    $params = ['salle' => $salle->getId(), 'date' => $dateStr, 'heure' => $heureStr];

    if ($excludeId) {
        $sql .= ' AND id != :excludeId';
        $params['excludeId'] = $excludeId;
    }

    $result = $conn->executeQuery($sql, $params)->fetchAssociative();

    return $result ? $this->find($result['id']) : null;
}

public function findJuryConflict(array $enseignants, $date, $heure, ?int $excludeId = null): ?Soutenance
{
    $conn = $this->getEntityManager()->getConnection();
    $dateStr = $date->format('Y-m-d');
    $heureStr = $heure->format('H:i:s');
    $ids = array_map(fn($e) => $e->getId(), $enseignants);

    $sql = 'SELECT id FROM soutenance WHERE date = :date AND heure = :heure AND (president_id IN (:ids) OR rapporteur_id IN (:ids) OR examinateur_id IN (:ids))';
    $params = ['date' => $dateStr, 'heure' => $heureStr, 'ids' => $ids];
    $types = ['ids' => ArrayParameterType::INTEGER];

    if ($excludeId) {
        $sql .= ' AND id != :excludeId';
        $params['excludeId'] = $excludeId;
    }

    $result = $conn->executeQuery($sql, $params, $types)->fetchAssociative();

    return $result ? $this->find($result['id']) : null;
}
}