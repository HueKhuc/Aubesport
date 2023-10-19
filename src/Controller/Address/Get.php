<?php

declare(strict_types=1);

namespace App\Controller\Address;

use App\Entity\Address;
use App\Exception\NotFound;
use App\Repository\AddressRepository;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Get extends AbstractController
{
    #[Route('/api/users/{uuid}/address', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the user\'s address',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Address::class))
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized'
    )]
    #[OA\Response(
        response: 404,
        description: 'Address not found'
    )]
    #[OA\Tag(name: 'Address')]
    public function __invoke(
        string $uuid,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        AddressRepository $addressRepository
    ): Response {
        $address = $addressRepository->findByUserUuid($uuid);

        if ($address === null) {
            throw new NotFound('Address not found');
        }

        return $this->json($address, 200);
    }
}
