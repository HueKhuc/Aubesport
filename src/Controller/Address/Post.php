<?php

declare(strict_types=1);

namespace App\Controller\Address;

use App\Entity\Address;
use App\Dto\Address as addressDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class Post extends AbstractController
{
    #[Route('/api/users/{uuid}/addresses', methods: ['POST'])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            ref: new Model(type: AddressDto::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the Address',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Address::class))
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\Tag(name: 'Address')]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        $addressDto = $serializer->deserialize(
            $request->getContent(),
            AddressDto::class,
            'json'
        );

        $errors = $validator->validate($addressDto);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $address = $serializer->deserialize(
            $serializer->serialize($addressDto, 'json'),
            Address::class,
            'json'
        );

        $entityManager->persist($address);
        $entityManager->flush();

        return $this->json($address);
    }
}
