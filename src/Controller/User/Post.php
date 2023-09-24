<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Dto\User as UserDto;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Post extends AbstractController
{
    #[Route('/api/users', methods: ['POST'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: UserDto::class)
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns the information of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserDto::class))
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation errors'
    )]
    #[OA\Response(
        response: 409,
        description: 'Conflict'
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        #[MapRequestPayload]
        UserDto $userDto
    ): Response {
        $user = $serializer->deserialize(
            $serializer->serialize($userDto, 'json'),
            User::class,
            'json'
        );

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, 409);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201);
    }
}
