<?php

declare(strict_types=1);

namespace App\Dto;

class TournamentOutput
{
    public string $uuid;

    public string $name;

    public \DateTimeInterface $startingDate;

    public ?\DateTimeInterface $endingDate = null;

    public \DateTimeImmutable $createdAt;

    public ?\DateTimeImmutable $modifiedAt = null;

    public ?\DateTimeImmutable $deletedAt = null;
}
