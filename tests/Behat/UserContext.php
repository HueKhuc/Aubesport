<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

class UserContext implements Context
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @AfterScenario
     */
    public function cleanUpUsers(AfterScenarioScope $event): void
    {
        exec('bin/console doctrine:query:sql "DELETE FROM user"');
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
}
