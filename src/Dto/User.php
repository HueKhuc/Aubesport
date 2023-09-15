<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    #[Assert\NotNull]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    public string $email;

    #[Assert\Regex('/^\w+/')]
    public ?string $firstName = null;

    #[Assert\Regex('/^\w+/')]
    public ?string $lastName = null;
    public ?string $pseudo = null;

    #[Assert\Regex('/^\w+/')]
    public ?string $bio = null;

    #[Assert\Choice(
        choices: ['autre', 'femme', 'homme'],
        message: 'Choose a valid gender.',
    )]
    public ?string $gender = null;

    #[Assert\LessThan('-16 years')]
    public ?\DateTimeImmutable $birthday = null;
}
