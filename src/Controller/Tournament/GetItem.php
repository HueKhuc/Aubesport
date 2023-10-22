<?php

declare(strict_types=1);

namespace App\Controller\Tournament;

use App\Dto\TournamentOutput;
use App\Entity\Tournament;
use App\Exception\NotFound;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
    ): Response {
        $tournament = $entityManager->getRepository(Tournament::class)->find($uuid);

        if ($tournament === null) {
            throw new NotFound('Tournament not found');
        }

        $tournamentOutput = $serializer->deserialize(
            $serializer->serialize($tournament, 'json'),
            TournamentOutput::class,
            'json'
        );

        return $this->json($tournamentOutput, 200);
    }
}
