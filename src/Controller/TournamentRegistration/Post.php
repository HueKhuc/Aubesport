<?php

declare(strict_types=1);

namespace App\Controller\TournamentRegistration;

use App\Dto\TournamentRegistrationInput;
use App\Entity\User;
use App\Entity\Tournament;
use App\Exception\Conflict;
use App\Exception\NotFound;
use OpenApi\Attributes as OA;
use App\Entity\TournamentRegistration;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\TournamentRegistrationOutput;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Post extends AbstractController
{
    #[Route('/api/tournament-registrations', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Create a tournament registration',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TournamentRegistrationOutput::class))
        )
    )]
    #[OA\Tag(name: 'TournamentRegistration')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        #[MapRequestPayload]
        TournamentRegistrationInput $tournamentRegistrationInput,
        TournamentRegistrationOutput $tournamentRegistrationOutput
    ): Response {
        $userUuid = $tournamentRegistrationInput->userUuid;
        $tournamentUuid = $tournamentRegistrationInput->tournamentUuid;

        $user = $entityManager->getRepository(User::class)->find($userUuid);
        $tournament = $entityManager->getRepository(Tournament::class)->find($tournamentUuid);

        if ($user === null) {
            throw new NotFound('User not found');
        }

        if ($tournament === null) {
            throw new NotFound('Tournament not found');
        }

        $tournamentRegistrations = $entityManager->getRepository(TournamentRegistration::class)->findAll();

        foreach ($tournamentRegistrations as $tournamentRegistration) {
            $registedUser = $tournamentRegistration->getUser();
            $registedTournament = $tournamentRegistration->getTournament();

            if ($user === $registedUser && $tournament === $registedTournament) {
                throw new Conflict('You have already registered');
            }
        }

        $tournamentRegistration = new TournamentRegistration();
        $tournamentRegistration
            ->setUser($user)
            ->setTournament($tournament);

        $entityManager->persist($tournamentRegistration);
        $entityManager->flush();

        $tournamentRegistrationOutput->uuid = $tournamentRegistration->getUuid();
        $tournamentRegistrationOutput->userUuid = $userUuid;
        $tournamentRegistrationOutput->tournamentUuid = $tournamentUuid;
        $tournamentRegistrationOutput->status = $tournamentRegistration->getStatus();
        $tournamentRegistrationOutput->createdAt = $tournamentRegistration->getCreatedAt();

        return $this->json($tournamentRegistrationOutput, 201);
    }
}
