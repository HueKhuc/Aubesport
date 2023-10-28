<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Dto\UserOutput;
use App\Exception\NotFound;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ObjectManipulation\TransferUserToOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetItem extends AbstractController
{
    #[Route('/api/users/{uuid}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the user\'s information',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserOutput::class))
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized'
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found'
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        string $uuid,
        EntityManagerInterface $entityManager,
        UserOutput $userOutput,
        TransferUserToOutput $transferUserToOutput,
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($uuid);

        if ($user === null) {
            throw new NotFound('User not found');
        }

        $transferUserToOutput($user, $userOutput);

        return $this->json($userOutput, 200);
    }
}
