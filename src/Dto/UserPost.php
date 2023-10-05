<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class UserPost extends UserInput
{
    #[Assert\NotNull]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    public string $email;

    #[Assert\NotNull]
    #[Assert\PasswordStrength([
        'minScore' => PasswordStrength::STRENGTH_WEAK,
        'message' => 'Password must include at least 10 characters, 1 number, 1 letter and 1 special character'
    ])]
    public string $password;
}
