<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Address
{
    #[Id]
    #[Column(type: Types::STRING)]
    private string $uuid;

    #[Column(type: Types::STRING)]
    private string $streetName;

    #[Column(type: Types::STRING)]
    private string $city;

    #[Column(type: Types::INTEGER)]
    private int $streetNumber;

    #[Column(type: Types::INTEGER)]
    private int $postalCode;

    public function __construct()
    {
        $this->uuid = Uuid::v4()->toRfc4122();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): static
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getStreetNumber(): int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): static
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }
}
