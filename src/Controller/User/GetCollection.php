<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Dto\UserOutput;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ObjectManipulation\TransferUserToOutput;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCollection extends AbstractController
{
    private const MAX_ELEMENTS_PER_PAGE = 50;

    private const DEFAUT_ELEMENTS_PER_PAGE = 10;

    #[Route('/api/users', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the information of all users',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserOutput::class))
        )
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        TransferUserToOutput $transferUserToOutput,
        #[MapQueryParameter]
        int $elementsPerPage = self::DEFAUT_ELEMENTS_PER_PAGE,
        #[MapQueryParameter]
        int $currentPage = 1
    ): Response {
        $elementsPerPage = ($elementsPerPage > self::MAX_ELEMENTS_PER_PAGE) ? self::DEFAUT_ELEMENTS_PER_PAGE : $elementsPerPage;

        $numberOfUsers = $entityManager->getRepository(User::class)->count([]);

        $totalOfPages = (int) ceil($numberOfUsers / $elementsPerPage);

        $offset = $elementsPerPage * ($currentPage - 1);

        $users = $entityManager->getRepository(User::class)->findBy(
            [],
            ['createdAt' => 'DESC'],
            $elementsPerPage,
            $offset
        );

        $usersOutput = [];
        foreach ($users as $user) {
            $userOutput = new UserOutput();
            $transferUserToOutput($user, $userOutput);
            $usersOutput[] = $userOutput;
        }

        $nextPage = ($currentPage < $totalOfPages) ? $currentPage + 1 : null;

        $previousPage = ($currentPage > 1) ? $currentPage - 1 : null;

        return $this->json([
            'elements' => $usersOutput,
            'totalOfPages' => $totalOfPages,
            'currentPage' => $currentPage,
            'elementsPerPage' => $elementsPerPage,
            'nextPage' => $nextPage,
            'previousPage' => $previousPage
        ]);
    }
}
