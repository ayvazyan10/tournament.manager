<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/teams', name: 'team_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
        ]);
    }

    #[Route('/team/create', name: 'team_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('team/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/team/{id}', name: 'team_show')]
    public function show(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/team/delete/{id}', name: 'team_delete', methods: ['POST'])]
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))) {
            $entityManager->remove($team);
            $entityManager->flush();

            $this->addFlash('success', 'Команда была успешно удалена.');
        }

        return $this->redirectToRoute('team_index');
    }
}