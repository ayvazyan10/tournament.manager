<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GameRepository;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Team $homeTeam;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Team $awayTeam;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\ManyToOne(targetEntity: Tournament::class, inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private Tournament $tournament;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $homeScore = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $awayScore = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(?int $homeScore): self
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(?int $awayScore): self
    {
        $this->awayScore = $awayScore;

        return $this;
    }
}