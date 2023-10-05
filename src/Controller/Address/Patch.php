<?php

declare(strict_types=1);

namespace App\Controller\Address;

use App\Entity\Address;
use App\Exception\NotFound;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use App\Dto\Address as AddressDto;
use App\Repository\AddressRepository;
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
    #[Route('/api/users/{uuid}/addresses', methods: ['PATCH'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: addressDto::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the user\'s information after modification',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Address::class))
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
    #[OA\Tag(name: 'Address')]
    public function __invoke(
        string $uuid,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        #[MapRequestPayload]
        AddressDto $addressDto,
        UpdateObject $updateObject,
        AddressRepository $addressRepository
    ): Response {
        $address = $addressRepository->findByUserUuid($uuid);

        if ($address === null) {
            throw new NotFound(Uuid::fromString($uuid));
        }

        $updateObject($address, $addressDto);

        $entityManager->flush();

        return $this->json($address, 200);
    }
}
