<?php

declare(strict_types=1);

namespace App\Controller\Image;

use App\Dto\Image;
use App\Exception\NotFound;
use App\Image\ImageUploader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Get extends AbstractController
{
    public function __construct(
        private string $targetDirectory
    ) {
    }

    #[Route('/api/users/{uuid}/image', methods: ['GET'])]
    public function __invoke(
        string $uuid
    ): Response {
        $finder = new Finder();
        $files = $finder->in($this->targetDirectory)->files()->name($uuid . '.*');


        $results = [];
        foreach ($files as $file) {
            $results[] = $file->getPathname();
        }

        if (count($results) === 0) {
            throw new NotFound('Image not found');
        }

        $explodedPath = explode('/', $results[0]);
        $filename = end($explodedPath);

        $response = new Response();
        $response->headers->set('Content-Type', 'image/jpeg');
        $response->headers->set('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setContent(file_get_contents($results[0]));

        return $response;
    }
}
