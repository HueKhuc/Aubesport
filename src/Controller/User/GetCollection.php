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

class GetCollection extends AbstractController
{
    #[Route('/api/users', methods: ['GET'])]
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
}
