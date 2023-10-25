<?php

declare(strict_types=1);

namespace App\Dto;

class TournamentRegistrationOutput
{
    public string $uuid;

    public string $userUuid;

    public string $tournamentUuid;

    public string $status = 'pending';

    public \DateTimeImmutable $createdAt;

    public ?\DateTimeImmutable $modifiedAt = null;

    public ?\DateTimeImmutable $deletedAt = null;
}
