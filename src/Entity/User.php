<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Entity]
#[UniqueEntity('email')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[Id]
    #[Column(type: Types::STRING)]
    private string $uuid;

    #[Column(type: Types::STRING, unique: true)]
    private string $email;

    #[Column(type: Types::STRING)]
    private string $role = 'ROLE_USER';

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $pseudo = null;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $bio = null;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $firstName = null;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $lastName = null;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $gender = null;

    #[Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $birthday = null;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[JoinColumn(name: 'address_uuid', referencedColumnName: 'uuid', nullable: true)]
    private ?Address $address = null;

    #[Column(type: Types::STRING)]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TournamentRegistration::class, orphanRemoval: true)]
    private Collection $tournamentRegistrations;

    public function __construct(Uuid $uuid = null)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->uuid = $uuid instanceof Uuid ? $uuid->toRfc4122() : Uuid::v4()->toRfc4122();
        $this->tournamentRegistrations = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function eraseCredentials(): void
    {

    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeImmutable $birthday): static
    {
        $this->birthday = $birthday;

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
            $tournamentRegistration->setUser($this);
        }

        return $this;
    }

    public function removeTournamentRegistration(TournamentRegistration $tournamentRegistration): static
    {
        if ($this->tournamentRegistrations->removeElement($tournamentRegistration)) {
            // set the owning side to null (unless already changed)
            if ($tournamentRegistration->getUser() === $this) {
                $tournamentRegistration->setUser(null);
            }
        }

        return $this;
    }
}
