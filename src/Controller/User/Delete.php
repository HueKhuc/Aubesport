<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Exception\NotFound;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Delete extends AbstractController
{
    #[Route('/api/users/{uuid}', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'Successfully deleted'
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found'
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        string $uuid,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($uuid);

        if ($user === null) {
            throw new NotFound(Uuid::fromString($uuid));
        }

        $user->updateDeletedAt();

        $entityManager->flush();

        return $this->json(null, 204);
    }
}
