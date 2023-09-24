<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCollection extends AbstractController
{
    #[Route('/api/users', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the information of all user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class))
        )
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        Request $request,
        #[MapQueryParameter]
        int $elementsPerPage = 10,
        #[MapQueryParameter]
        int $currentPage = 1
    ): Response {
        if ($elementsPerPage > 50) {
            $elementsPerPage = 10;
        }

        $numberOfUsers = $entityManager->getRepository(User::class)->count([]);
        $totalOfPages = (int) ceil($numberOfUsers / $elementsPerPage);

        $offset = $elementsPerPage * ($currentPage - 1);

        $users = $entityManager->getRepository(User::class)->findBy(
            [],
            ['createdAt' => 'DESC'],
            $elementsPerPage,
            $offset
        );

        $nextPage = ($currentPage < $totalOfPages) ? $currentPage + 1 : null;

        $previousPage = ($currentPage > 1) ? $currentPage - 1 : null;

        return $this->json([
            'elements' => $users,
            'totalOfPages' => $totalOfPages,
            'currentPage' => $currentPage,
            'elementsPerPage' => $elementsPerPage,
            'nextPage' => $nextPage,
            'previousPage' => $previousPage
        ]);
    }
}
