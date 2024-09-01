<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    //--> all upcoming games
    public function findUpcomingGames(\DateTimeInterface $currentDate): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.date >= :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->orderBy('g.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //--> upcoming games by tournament
    public function findUpcomingGamesByTournament(int $tournamentId, \DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.tournament = :tournamentId')
            ->andWhere('g.date >= :date')
            ->setParameter('tournamentId', $tournamentId)
            ->setParameter('date', $date)
            ->orderBy('g.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //--> past games by tournament
    public function findPastGamesByTournament(int $tournamentId, \DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.tournament = :tournamentId')
            ->andWhere('g.date < :date')
            ->setParameter('tournamentId', $tournamentId)
            ->setParameter('date', $date)
            ->orderBy('g.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}