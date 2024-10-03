<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Scores;
use Doctrine\ORM\EntityManagerInterface;

class ScoreController extends AbstractController
{
    #[Route('/score', name: 'app_score')]
    public function index(): Response
    {
        return $this->render('score/index.html.twig', [
            'controller_name' => 'ScoreController',
        ]);
    }

    #[Route('/score/submit', name: 'submit_score', methods: ['POST'])]
    public function submitScore(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['score']) && isset($data['difficulty'])) {
            // Create a new Score object and populate fields
            $score = new Scores();  // Ensure entity name is correct
            $score->setScore($data['score']);
            $score->setDifficulty($data['difficulty']);
            $score->setCreatedAt(new \DateTime());

            try {
                $entityManager->persist($score);
                $entityManager->flush();
                return new JsonResponse([
                    'status' => 'success',
                    'message' => 'Score saved successfully!'
                ], 200);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Error saving score: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // Return is now correctly inside the function
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid data!'
            ], 400);
        }
    }
}