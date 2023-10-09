<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Dto\UserOutput;
use App\Entity\User;
use OpenApi\Attributes as OA;
use App\Dto\UserPost;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Post extends AbstractController
{
    #[Route('/api/users', methods: ['POST'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: UserPost::class)
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns the information of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserOutput::class))
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
        UserPost $userDto,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $serializer->deserialize(
            $serializer->serialize($userDto, 'json'),
            User::class,
            'json'
        );

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, 409);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $userOutput = $serializer->deserialize(
            $serializer->serialize($user, 'json'),
            UserOutput::class,
            'json'
        );

        return $this->json($userOutput, 201);
    }
}
