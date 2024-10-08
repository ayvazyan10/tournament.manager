<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    //--> gives all teams ordered by name
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //--> teams without tournaments
    public function findTeamsWithoutTournament(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.tournaments', 'tr')
            ->andWhere('tr.id IS NULL')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //--> team by id
    public function findTeamsByTournament(int $tournamentId): array
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.tournaments', 'tr')
            ->andWhere('tr.id = :tournamentId')
            ->setParameter('tournamentId', $tournamentId)
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}