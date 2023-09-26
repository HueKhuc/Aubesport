<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Dto\UserPatch;
use App\ObjectManipulation\UpdateObject;
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

class Patch extends AbstractController
{
    #[Route('/api/users/{uuid}', methods: ['PATCH'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: UserPatch::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the user\'s information after modification',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class))
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation errors'
    )]

    #[OA\Tag(name: 'User')]
    public function __invoke(
        string $uuid,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        #[MapRequestPayload]
        UserPatch $userDto,
        UpdateObject $service
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($uuid);

        if ($user === null) {
            return $this->json($user, 400);
        }

        $service($user, $userDto);
        $user->updateModifiedAt();

        $entityManager->flush();

        return $this->json($user, 200);
    }
}
