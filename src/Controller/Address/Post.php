<?php

declare(strict_types=1);

namespace App\Controller\Address;

use App\Entity\User;
use App\Entity\Address;
use App\Exception\NotFound;
use OpenApi\Attributes as OA;
use App\Dto\Address as AddressDto;
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
    #[Route('/api/users/{uuid}/address', methods: ['POST'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: AddressDto::class)
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns user\'s address',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Address::class))
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation errors'
    )]
    #[OA\Tag(name: 'Address')]
    public function __invoke(
        string $uuid,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        #[MapRequestPayload]
        AddressDto $addressDto
    ): Response {
        $address = $serializer->deserialize(
            $serializer->serialize($addressDto, 'json'),
            Address::class,
            'json'
        );

        $user = $entityManager->getRepository(User::class)->find($uuid);

        if ($user === null) {
            throw new NotFound('User not found');
        }

        $user->setAddress($address);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($address, 201);
    }
}
