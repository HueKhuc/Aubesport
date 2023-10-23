<?php

declare(strict_types=1);

namespace App\Controller\Tournament;

use App\Dto\TournamentOutput;
use App\Entity\Tournament;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCollection extends AbstractController
{
    private const MAX_ELEMENTS_PER_PAGE = 50;

    private const DEFAUT_ELEMENTS_PER_PAGE = 10;

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
        #[MapQueryParameter]
        int $elementsPerPage = self::DEFAUT_ELEMENTS_PER_PAGE,
        #[MapQueryParameter]
        int $currentPage = 1
    ): Response {
        $elementsPerPage = ($elementsPerPage > self::MAX_ELEMENTS_PER_PAGE) ? self::DEFAUT_ELEMENTS_PER_PAGE : $elementsPerPage;

        $numberOfTournaments = $entityManager->getRepository(Tournament::class)->count([]);

        $totalOfPages = (int) ceil($numberOfTournaments / $elementsPerPage);

        $offset = $elementsPerPage * ($currentPage - 1);

        $tournaments = $entityManager->getRepository(Tournament::class)->findBy(
            [],
            ['createdAt' => 'DESC'],
            $elementsPerPage,
            $offset
        );

        $tournamentsOutput = [];
        foreach ($tournaments as $tournament) {
            $tournamentOutput = new TournamentOutput();

            $tournamentOutput->uuid = $tournament->getUuid();
            $tournamentOutput->name = $tournament->getName();
            $tournamentOutput->startingDate = $tournament->getStartingDate();
            $tournamentOutput->endingDate = $tournament->getEndingDate();
            $tournamentOutput->createdAt = $tournament->getCreatedAt();
            $tournamentOutput->modifiedAt = $tournament->getModifiedAt();
            $tournamentOutput->deletedAt = $tournament->getDeletedAt();

            $tournamentsOutput[] = $tournamentOutput;
        }

        $nextPage = ($currentPage < $totalOfPages) ? $currentPage + 1 : null;

        $previousPage = ($currentPage > 1) ? $currentPage - 1 : null;

        return $this->json([
            'elements' => $tournamentsOutput,
            'totalOfPages' => $totalOfPages,
            'currentPage' => $currentPage,
            'elementsPerPage' => $elementsPerPage,
            'nextPage' => $nextPage,
            'previousPage' => $previousPage
        ]);
    }
}
