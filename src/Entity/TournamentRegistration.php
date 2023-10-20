<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TournamentRegistrationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TournamentRegistrationRepository::class)]
class TournamentRegistration
{
    #[ORM\Id]
    #[ORM\Column]
    private string $uuid;

    #[ORM\ManyToOne(inversedBy: 'tournamentRegistrations')]
    #[ORM\JoinColumn(referencedColumnName: 'uuid', nullable: false)]
    private Tournament $tournament;

    #[ORM\Column(length: 255)]
    private string $status = 'pending';

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentRegistrations')]
    #[ORM\JoinColumn(referencedColumnName: 'uuid', nullable: false)]
    private User $user;

    public function __construct()
    {
        $this->uuid = Uuid::v4()->toRfc4122();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function updateModifiedAt(): static
    {
        $this->modifiedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function updateDeletedAt(): static
    {
        $this->deletedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
