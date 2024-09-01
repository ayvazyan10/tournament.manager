<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Турнир с таким названием уже существует.')]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $slug;

    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'tournaments')]
    #[ORM\JoinTable(name: 'tournament_teams')]
    private Collection $teams;

    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'tournament', cascade: ['persist', 'remove'])]
    private Collection $games;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        $this->teams->removeElement($team);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function generateSlug(SluggerInterface $slugger): void
    {
        $this->slug = strtolower($slugger->slug($this->name)->toString());
    }
}