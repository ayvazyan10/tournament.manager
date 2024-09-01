<?php

declare(strict_types=1);

namespace App\Controller;

use App\Action\GenerateMatchesAction;
use App\Entity\Tournament;
use App\Entity\Team;
use App\Form\TournamentType;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TournamentController extends AbstractController
{
    private GenerateMatchesAction $generateMatchesAction;

    public function __construct(GenerateMatchesAction $generateMatchesAction)
    {
        $this->generateMatchesAction = $generateMatchesAction;
    }

    #[Route('/tournaments', name: 'tournament_index')]
    public function index(TournamentRepository $repository): Response
    {
        $tournaments = $repository->findAll();
        return $this->render('tournament/index.html.twig', [
            'tournaments' => $tournaments,
        ]);
    }

    #[Route('/tournament/create', name: 'tournament_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $tournament = new Tournament();

        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournament->generateSlug($slugger);

            //--> Если не выбраны команды, добавляем все доступные команды в турнир
            if ($tournament->getTeams()->isEmpty()) {
                $allTeams = $entityManager->getRepository(Team::class)->findAll();
                foreach ($allTeams as $team) {
                    $tournament->addTeam($team);
                }
            }

            $entityManager->persist($tournament);
            $entityManager->flush();

            $this->generateMatchesAction->execute($tournament);

            return $this->redirectToRoute('tournament_index');
        }

        return $this->render('tournament/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tournament/{slug}', name: 'tournament_show')]
    public function show(string $slug, TournamentRepository $tournamentRepository): Response
    {
        $tournament = $tournamentRepository->findOneBySlug($slug);

        if (!$tournament) {
            throw $this->createNotFoundException('Турнир не найден');
        }

        $matches = $tournament->getGames();

        return $this->render('tournament/show.html.twig', [
            'tournament' => $tournament,
            'matches' => $matches,
        ]);
    }

    #[Route('/tournament/delete/{id}', name: 'tournament_delete', methods: ['POST'])]
    public function delete(Request $request, Tournament $tournament, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tournament->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tournament);
            $entityManager->flush();

            $this->addFlash('success', 'Турнир был успешно удален.');
        } else {
            $this->addFlash('error', 'Ошибка CSRF.');
        }

        return $this->redirectToRoute('tournament_index');
    }
}
