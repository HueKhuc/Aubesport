<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Delete extends AbstractController
{
    #[Route('/api/users/{uuid}', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'Successfully deleted'
    )]
    #[OA\Response(
        response: 400,
        description: 'User not found'
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        string $uuid,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($uuid);

        if ($user === null) {
            return $this->json($user, 400);
        }

        $user->updateDeletedAt();

        $entityManager->flush();

        return $this->json($user, 204);
    }
}
