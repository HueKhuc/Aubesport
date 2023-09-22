<?php

declare(strict_types=1);

namespace App\Controller\address;

use App\Entity\Address;
use App\Dto\Address as addressDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Post extends AbstractController
{
    #[Route('/api/addresss', methods: ['POST'])]
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
