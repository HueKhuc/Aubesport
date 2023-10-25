<?php

declare(strict_types=1);

namespace App\Controller\Tournament;

use App\Dto\TournamentOutput;
use App\Entity\Tournament;
use App\ObjectManipulation\TransferTournamentToOutput;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCollection extends AbstractController
{
    #[Route('/api/tournaments', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the information of all tournaments',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TournamentOutput::class))
        )
    )]
    #[OA\Tag(name: 'Tournament')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        TransferTournamentToOutput $transferTournamentToOutput
    ): Response {
        $tournaments = $entityManager->getRepository(Tournament::class)->findAll();

        $tournamentsOutput = [];

        foreach ($tournaments as $tournament) {
            $tournamentOutput = new TournamentOutput();

            $transferTournamentToOutput($tournament, $tournamentOutput);

            $tournamentsOutput[] = $tournamentOutput;
        }

        return $this->json($tournamentsOutput, 200);
    }
}
