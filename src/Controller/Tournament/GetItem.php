<?php

declare(strict_types=1);

namespace App\Controller\Tournament;

use App\Entity\Tournament;
use App\Exception\NotFound;
use App\Dto\TournamentOutput;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ObjectManipulation\TransferTournamentToOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetItem extends AbstractController
{
    #[Route('/api/tournaments/{uuid}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the tournament\'s information',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TournamentOutput::class))
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized'
    )]
    #[OA\Response(
        response: 404,
        description: 'Tournament not found'
    )]
    #[OA\Tag(name: 'Tournament')]
    public function __invoke(
        string $uuid,
        EntityManagerInterface $entityManager,
        TournamentOutput $tournamentOutput,
        TransferTournamentToOutput $transferTournamentToOutput
    ): Response {
        $tournament = $entityManager->getRepository(Tournament::class)->find($uuid);

        if ($tournament === null) {
            throw new NotFound('Tournament not found');
        }

        $transferTournamentToOutput($tournament, $tournamentOutput);

        return $this->json($tournamentOutput, 200);
    }
}
