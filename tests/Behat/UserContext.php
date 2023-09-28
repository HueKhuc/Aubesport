<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Tester\Exception\PendingException;
use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Uid\Uuid;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

class UserContext implements Context
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        private AddressContext $addressContext
    ) {
    }

    /**
     * @AfterScenario
     */
    public function cleanUpUsers(AfterScenarioScope $event): void
    {
        exec('bin/console doctrine:query:sql "DELETE FROM user, address"');
    }

    /**
     * @Given there is an existant user with email :email and uuid :uuid
     */
    public function aUserWithEmail(string $email, string $uuid): void
    {
        $user = new User(Uuid::fromString($uuid));
        $user->setEmail($email);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @Given there are :numberOfUsers users in the database
     */
    public function thereAreUsersInTheDatabase(int $numberOfUsers): void
    {
        for ($i = 1; $i <= $numberOfUsers; $i++) {
            $user = new User();
            $user->setEmail('email'.$i.'@gmail.com');
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }

    /**
     * @Given there is an existent user with an email :email, uuid :uuid, and an address with streetName :streetName, streetNumber :streetNumber, city :city, postalCode :postalCode
     */
    public function aUserWithEmailAndAddress(
        string $email,
        string $uuid,
        string $streetName,
        string $streetNumber,
        string $city,
        string $postalCode
    ): void {
        // ** Copie colle le code pour créer une nouvelle adresse ***
        $address = new Address();
        $address->setStreetName($streetName);
        $address->setStreetNumber($streetNumber);
        $address->setCity($city);
        $address->setPostalCode($postalCode);

        $this->entityManager->persist($address);

        // Créez un utilisateur avec l'adresse associée.
        $user = new User(Uuid::fromString($uuid));
        $user->setEmail($email);
        $user->setAddress($address);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
