<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Behat\Behat\Context\Context;

class AddressContext implements Context
{
    private ?Address $lastCreatedAddress = null;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function aUserAddress(
        string $streetName,
        string $streetNumber,
        string $city,
        string $postalCode
    ): void {
        $address = new Address();
        $address->setStreetName($streetName);
        $address->setStreetNumber($streetNumber);
        $address->setCity($city);
        $address->setPostalCode($postalCode);

        $this->entityManager->persist($address);
        $this->entityManager->flush();

        // Enregistrez l'adresse créée pour pouvoir la récupérer plus tard si nécessaire.
        $this->lastCreatedAddress = $address;
    }

    // Méthode pour récupérer la dernière adresse créée.
    public function getLastCreatedAddress(): ?Address
    {
        return $this->lastCreatedAddress;
    }
}
