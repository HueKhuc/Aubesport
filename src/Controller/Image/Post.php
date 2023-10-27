<?php

declare(strict_types=1);

namespace App\Controller\Image;

use App\Dto\Image;
use App\Image\ImageUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Post extends AbstractController
{
    #[Route('/api/users/{uuid}/image', methods: ['POST'])]
    public function __invoke(
        Request $request,
        ImageUploader $imageUploader,
        string $uuid,
        ValidatorInterface $validator
    ): Response {
        $imageFile = null;

        if ($request->files->has('image')) {
            $imageFile = $request->files->get('image');
            assert($imageFile instanceof UploadedFile);
        }

        $image = new Image();
        $image->imageFile = $imageFile;


        $errors = $validator->validate($image);
        if (count($errors) > 0) {
            return $this->json($errors, 422);
        }

        assert($imageFile instanceof UploadedFile);
        $imageUploader($imageFile, $uuid);

        return new Response(status: 204);
    }
}
