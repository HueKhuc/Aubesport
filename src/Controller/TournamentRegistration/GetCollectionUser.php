<?php

declare(strict_types=1);

namespace App\Controller\TournamentRegistration;

use App\Exception\NotFound;
use App\ObjectManipulation\TransferTournamentRegistrationToOutput;
use App\Repository\TournamentRegistrationRepository;
use OpenApi\Attributes as OA;
use App\Dto\TournamentRegistrationOutput;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCollectionUser extends AbstractController
{
    #[Route('/api/users/{uuid}/tournament-registrations', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the information of all tournament registrations of user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TournamentRegistrationOutput::class))
        )
    )]
    #[OA\Tag(name: 'Tournament Registration')]
    public function __invoke(
        string $uuid,
        TournamentRegistrationRepository $tournamentRegistrationRepository,
        TournamentRegistrationOutput $tournamentRegistrationOutput,
        TransferTournamentRegistrationToOutput $transferTournamentRegistrationToOutput,
    ): Response {
        $tournamentRegistrations = $tournamentRegistrationRepository->findByUserUuid($uuid);

        if (count($tournamentRegistrations) === 0) {
            throw new NotFound('Tournament Registration not found');
        }

        $tournamentRegistrationsOutput = [];

        foreach ($tournamentRegistrations as $tournamentRegistration) {
            $tournamentRegistrationOutput = new TournamentRegistrationOutput();

            $transferTournamentRegistrationToOutput($tournamentRegistration, $tournamentRegistrationOutput);

            $tournamentRegistrationsOutput[] = $tournamentRegistrationOutput;
        }

        return $this->json($tournamentRegistrationsOutput, 200);
    }
}
