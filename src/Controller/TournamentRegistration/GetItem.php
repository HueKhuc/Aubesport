<?php

declare(strict_types=1);

namespace App\Controller\TournamentRegistration;

use App\Entity\TournamentRegistration;
use App\Exception\NotFound;
use App\ObjectManipulation\TransferTournamentRegistrationToOutput;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\TournamentRegistrationOutput;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetItem extends AbstractController
{
    #[Route('/api/tournament-registrations/{uuid}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the tournament registration\'s information',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TournamentRegistrationOutput::class))
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized'
    )]
    #[OA\Response(
        response: 404,
        description: 'Tournament registration not found'
    )]
    #[OA\Tag(name: 'Tournament Registration')]
    public function __invoke(
        string $uuid,
        EntityManagerInterface $entityManager,
        TransferTournamentRegistrationToOutput $transferTournamentRegistrationToOutput,
        TournamentRegistrationOutput $tournamentRegistrationOutput
    ): Response {
        $tournamentRegistration = $entityManager->getRepository(TournamentRegistration::class)->find($uuid);

        if ($tournamentRegistration === null) {
            throw new NotFound('Tournament registration not found');
        }

        $transferTournamentRegistrationToOutput($tournamentRegistration, $tournamentRegistrationOutput);

        return $this->json($tournamentRegistrationOutput, 200);
    }
}
