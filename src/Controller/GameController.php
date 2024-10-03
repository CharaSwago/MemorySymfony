<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        $cards = [
            ['name' => 'flower_hill', 'front' => 'flower_hill.jpg', 'back' => 'back.jpg'],
            ['name' => 'adam_tree', 'front' => 'adam_tree.jpg', 'back' => 'back.jpg'],
            ['name' => 'flower_hill', 'front' => 'flower_hill.jpg', 'back' => 'back.jpg'],
            ['name' => 'adam_tree', 'front' => 'adam_tree.jpg', 'back' => 'back.jpg']
        ];

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'cards' => $cards
        ]);
    }
}
