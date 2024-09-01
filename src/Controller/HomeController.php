<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //--> Получаем все предстоящие матчи, начиная с текущего времени
        $games = $entityManager->getRepository(Game::class)->findUpcomingGames(new \DateTime());

        return $this->render('index.html.twig', [
            'games' => $games,
        ]);
    }
}