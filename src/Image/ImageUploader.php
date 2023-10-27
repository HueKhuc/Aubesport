<?php

declare(strict_types=1);

namespace App\Image;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    public function __construct(
        private string $targetDirectory
    ) {
    }

    public function __invoke(
        UploadedFile $file,
        string $userUuid
    ): void {
        $fileName = $userUuid.'.'.$file->guessExtension();

        $file->move($this->targetDirectory, $fileName);
    }
}
