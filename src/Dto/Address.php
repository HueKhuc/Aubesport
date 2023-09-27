<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

class Address
{
    #[OA\Property(type: 'string', maxLength: 255, example: 'This is my streetName')]
    #[Assert\Regex('/^\w+/')]
    public ?string $streetName = null;

    #[OA\Property(type: 'string', maxLength: 255, example: 'This is my city')]
    #[Assert\Regex('/^\w+/')]
    public ?string $city = null;

    #[OA\Property(type: 'string', maxLength: 255, example: '60008')]
    #[Assert\Regex(
        pattern: '/^\d{5}$/',
        message: 'Le code postal doit contenir exactement 5 chiffres.'
    )]
    public ?string $postalCode = null;

    #[OA\Property(type: 'string', maxLength: 255, example: 'This is my streetNumber')]
    #[Assert\Regex('/^\w+/')]
    public ?string $streetNumber = null;
}
