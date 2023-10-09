<?php

declare(strict_types=1);

namespace App\Dto;

class UserOutput
{
    public string $uuid;

    public string $email;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $pseudo = null;

    public ?string $bio = null;

    public ?string $gender = null;

    public ?\DateTimeImmutable $birthday = null;

    public \DateTimeImmutable $createdAt;

    public ?\DateTimeImmutable $modifiedAt = null;

    public ?\DateTimeImmutable $deletedAt = null;
}
