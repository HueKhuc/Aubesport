<?php
namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Entity]
#[UniqueEntity('password')]
class Authentication implements PasswordAuthenticatedUserInterface
{
    #[Id]
    #[Column(type: Types::STRING)]
    private string $uuid;

    #[Column(type: Types::STRING, unique: true)]
    private string $password;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $modifiedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->uuid = Uuid::v4()->toRfc4122();
    }
    
    public function getUuid(): string
    {
        return $this->uuid;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function setPassword($password): static
    {
        $this->password = $password;

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

    public function updateModifiedAt($modifiedAt): static
    {
        $this->modifiedAt = new \DateTimeImmutable();

        return $this;
    }
}