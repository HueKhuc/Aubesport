<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[Entity]
#[UniqueEntity('email')]
class User
{
    #[Id]
    #[Column(type: Types::STRING)]
    private string $uuid;

    #[Column(type: Types::STRING, unique: true)]
    private string $email;

    #[Column(type: Types::STRING, nullable:true)]
    private ?string $pseudo = null;

    #[Column(type: Types::STRING, nullable:true)]
    private ?string $bio = null;

    #[Column(type: Types::STRING, nullable:true)]
    private ?string $firstName = null;

    #[Column(type: Types::STRING, nullable:true)]
    private ?string $lastName = null;

    #[Column(type: Types::STRING, nullable:true)]
    private ?string $gender = null;

    #[Column(type: Types::DATE_IMMUTABLE, nullable:true)]
    private ?\DateTimeImmutable $birthday;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable:true)]
    private ?\DateTimeImmutable $modifiedAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable:true)]
    private ?\DateTimeImmutable $deletedAt;

    #[OneToOne(targetEntity: Address::class)]
    #[JoinColumn(name: 'address_uuid', referencedColumnName: 'uuid', nullable:true)]
    private ?Address $address = null;

    public function __construct(Uuid $uuid = null)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->uuid = $uuid instanceof Uuid ? $uuid->toRfc4122() : Uuid::v4()->toRfc4122();
    }

    public function getUuid(): string
    {
        return $this->uuid;
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
}
