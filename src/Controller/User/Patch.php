<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Dto\UserOutput;
use App\Entity\User;
use App\Dto\UserPatch;
use App\Exception\NotFound;
use OpenApi\Attributes as OA;
use App\ObjectManipulation\UpdateObject;
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
            items: new OA\Items(ref: new Model(type: UserOutput::class))
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation errors'
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
        ValidatorInterface $validator,
        #[MapRequestPayload]
        UserPatch $userDto,
        UpdateObject $updateObject
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($uuid);

        if ($user === null) {
            throw new NotFound('User not found');
        }

        $updateObject($user, $userDto);
        $user->updateModifiedAt();

        $entityManager->flush();

        $userOutput = $serializer->deserialize(
            $serializer->serialize($user, 'json'),
            UserOutput::class,
            'json'
        );

        return $this->json($userOutput, 200);
    }
}
