<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Dto\User as userDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class Get extends AbstractController
{
    #[Route('/api/users', methods: ['GET'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: UserDto::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the information of all user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserDto::class))
        )
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ): Response {
        $users = $entityManager->getRepository(User::class)->findAll();

        if (count($users) === 0) {
            return $this->json('No user', 200);
        }

        $userDtos = [];
        foreach($users as $user) {
            $userDto = $serializer->deserialize(
                $serializer->serialize($user, 'json'),
                userDto::class,
                'json'
            );
            $userDtos[] = $userDto;
        }

        return $this->json($userDtos, 200);
    }

    #[Route('/api/users/{uuid}', methods: ['GET'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: UserDto::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the information of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserDto::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found'
    )]
    #[OA\Tag(name: 'User')]
    public function index(
        string $uuid,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($uuid);

        if ($user === null) {
            return $this->json('User not found', 404);
        }

        $userDto = $serializer->deserialize(
            $serializer->serialize($user, 'json'),
            userDto::class,
            'json'
        );

        return $this->json($userDto, 200);
    }
}
