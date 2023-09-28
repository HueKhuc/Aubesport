<?php

declare(strict_types=1);

namespace App\Controller\Address;

use App\Entity\Address;
use App\Entity\User;
use App\Dto\Address as AddressDto;
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
    #[Route('/api/users/{uuid}/addresses', methods: ['PATCH'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: AddressDto::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the address\'s information after modification',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Address::class))
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation errors'
    )]

    #[OA\Tag(name: 'address')]
    public function __invoke(
        string $uuid,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        #[MapRequestPayload]
        AddressDto $addressDto,
        UpdateObject $updateObject
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($uuid);
        if ($user === null) {
            return $this->json($user, 400);
        }
        $addressUuid = $user->getAddress();
        $address = $entityManager->getRepository(Address::class)->find($addressUuid);
        dd($address);
        $updateObject($address, $addressDto);
        
        $entityManager->flush();
        

        return $this->json($address, 200);
    }
}
