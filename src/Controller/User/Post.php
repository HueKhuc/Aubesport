<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Dto\User as UserDto;
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
    #[Route('/api/users', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        $userDto = $serializer->deserialize(
            $request->getContent(),
            UserDto::class,
            'json'
        );

        $errors = $validator->validate($userDto);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $user = $serializer->deserialize(
            $serializer->serialize($userDto, 'json'),
            User::class,
            'json'
        );

        // $entityManager->persist($user);
        // $entityManager->flush();

        return $this->json($user);
    }
}
