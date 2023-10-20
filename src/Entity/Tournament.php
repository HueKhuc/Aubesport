<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\Column]
    private string $uuid;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $startingDate;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endingDate = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentRegistration::class, orphanRemoval: true)]
    private Collection $tournamentRegistrations;

    public function __construct()
    {
        $this->tournamentRegistrations = new ArrayCollection();
        $this->uuid = Uuid::v4()->toRfc4122();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartingDate(): \DateTimeInterface
    {
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeInterface $startingDate): static
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->endingDate;
    }

    public function setEndingDate(?\DateTimeInterface $endingDate): static
    {
        $this->endingDate = $endingDate;

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

    public function setModifiedAt(?\DateTimeImmutable $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return Collection<int, TournamentRegistration>
     */
    public function getTournamentRegistrations(): Collection
    {
        return $this->tournamentRegistrations;
    }

    public function addTournamentRegistration(TournamentRegistration $tournamentRegistration): static
    {
        if (!$this->tournamentRegistrations->contains($tournamentRegistration)) {
            $this->tournamentRegistrations->add($tournamentRegistration);
            $tournamentRegistration->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentRegistration(TournamentRegistration $tournamentRegistration): static
    {
        if ($this->tournamentRegistrations->removeElement($tournamentRegistration)) {
            // set the owning side to null (unless already changed)
            if ($tournamentRegistration->getTournament() === $this) {
                $tournamentRegistration->setTournament(null);
            }
        }

        return $this;
    }
}
