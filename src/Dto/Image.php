<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class Image
{
    #[Assert\File(
        maxSize: '1024k',
        extensions: ['png', 'jpg', 'jpeg'],
        extensionsMessage: 'Please upload a valid image',
    )]
    #[Assert\NotNull]
    public ?UploadedFile $imageFile = null;
}
