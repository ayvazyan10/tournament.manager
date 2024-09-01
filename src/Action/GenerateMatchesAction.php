<?php

declare(strict_types=1);

namespace App\Action;

use App\Entity\Tournament;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class GenerateMatchesAction
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(Tournament $tournament): void
    {
        $teams = $tournament->getTeams()->toArray();
        $totalTeams = count($teams);

        if ($totalTeams < 2) {
            return; //--> Не создаем игры, если недостаточно команд
        }

        //--> Генерация игр по алгоритму кругового турнира
        for ($i = 0; $i < $totalTeams - 1; $i++) {
            for ($j = 0; $j < $totalTeams / 2; $j++) {
                $home = $teams[$j];
                $away = $teams[$totalTeams - 1 - $j];

                $game = new Game();
                $game->setHomeTeam($home);
                $game->setAwayTeam($away);
                $game->setDate((new \DateTime())->modify('+' . ($i + 1) . ' days'));
                $game->setTournament($tournament);

                $this->entityManager->persist($game);
            }

            //--> Смещение команд для следующего тура
            $temp = array_splice($teams, 1, 1);
            array_splice($teams, $totalTeams - 1, 0, $temp);
        }

        $this->entityManager->flush();
    }
}