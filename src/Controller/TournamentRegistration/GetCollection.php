<?php

declare(strict_types=1);

namespace App\Controller\TournamentRegistration;

use App\Dto\TournamentRegistrationOutput;
use App\Entity\TournamentRegistration;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCollection extends AbstractController
{
    private const MAX_ELEMENTS_PER_PAGE = 50;

    private const DEFAUT_ELEMENTS_PER_PAGE = 10;

    #[Route('/api/tournament-registrations', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the information of all tournament registrations',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TournamentRegistrationOutput::class))
        )
    )]
    #[OA\Tag(name: 'Tournament Registration')]
    public function __invoke(
        TournamentRegistrationOutput $tournamentRegistrationOutput,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        Request $request,
        #[MapQueryParameter]
        int $elementsPerPage = self::DEFAUT_ELEMENTS_PER_PAGE,
        #[MapQueryParameter]
        int $currentPage = 1
    ): Response {
        $elementsPerPage = ($elementsPerPage > self::MAX_ELEMENTS_PER_PAGE) ? self::DEFAUT_ELEMENTS_PER_PAGE : $elementsPerPage;

        $numberOfTournamentRegistrations = $entityManager->getRepository(TournamentRegistration::class)->count([]);

        $totalOfPages = (int) ceil($numberOfTournamentRegistrations / $elementsPerPage);

        $offset = $elementsPerPage * ($currentPage - 1);

        $tournamentRegistrations = $entityManager->getRepository(TournamentRegistration::class)->findBy(
            [],
            ['createdAt' => 'DESC'],
            $elementsPerPage,
            $offset
        );

        $tournamentRegistrationsOutput = [];

        foreach ($tournamentRegistrations as $tournamentRegistration) {
            $tournamentRegistrationOutput->uuid = $tournamentRegistration->getUuid();
            $tournamentRegistrationOutput->userUuid = $tournamentRegistration->getUser()->getUuid();
            $tournamentRegistrationOutput->tournamentUuid = $tournamentRegistration->getTournament()->getUuid();
            $tournamentRegistrationOutput->status = $tournamentRegistration->getStatus();
            $tournamentRegistrationOutput->createdAt = $tournamentRegistration->getCreatedAt();
            $tournamentRegistrationOutput->modifiedAt = $tournamentRegistration->getModifiedAt();
            $tournamentRegistrationOutput->deletedAt = $tournamentRegistration->getDeletedAt();

            $tournamentRegistrationsOutput[] = $tournamentRegistrationOutput;
        }

        $nextPage = ($currentPage < $totalOfPages) ? $currentPage + 1 : null;

        $previousPage = ($currentPage > 1) ? $currentPage - 1 : null;

        return $this->json([
            'elements' => $tournamentRegistrationsOutput,
            'totalOfPages' => $totalOfPages,
            'currentPage' => $currentPage,
            'elementsPerPage' => $elementsPerPage,
            'nextPage' => $nextPage,
            'previousPage' => $previousPage
        ]);
    }
}
