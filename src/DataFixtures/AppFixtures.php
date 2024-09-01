<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Team;
use App\Entity\Tournament;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Action\GenerateMatchesAction;

class AppFixtures extends Fixture
{
    private SluggerInterface $slugger;
    private GenerateMatchesAction $generateMatchesAction;

    public function __construct(SluggerInterface $slugger, GenerateMatchesAction $generateMatchesAction)
    {
        $this->slugger = $slugger;
        $this->generateMatchesAction = $generateMatchesAction;
    }

    public function load(ObjectManager $manager): void
    {
        $teams = [];

        for ($i = 1; $i <= 10; $i++) {
            $team = new Team();
            $team->setName('Команда ' . $i);
            $manager->persist($team);
            $teams[] = $team;
        }

        for ($i = 1; $i <= 3; $i++) {
            $tournament = new Tournament();
            $tournament->setName('Турнир ' . $i);
            $tournament->generateSlug($this->slugger);

            foreach ($teams as $team) {
                $tournament->addTeam($team);
            }

            $manager->persist($tournament);

            //--> Генерация игр
            $this->generateMatchesAction->execute($tournament);
        }

        $manager->flush();
    }
}