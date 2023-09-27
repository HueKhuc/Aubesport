<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\User;
use Monolog\Test\TestCase;
use Symfony\Component\Uid\Uuid;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Symfony\Component\PropertyAccess\PropertyAccess;

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

    /**
     * @Then the field :column in the database of the user having the uuid :uuid should not be null
     */
    public function theFieldShouldNotBeNull(string $column, string $uuid): void
    {
        $user = $this->entityManager->getRepository(User::class)->find($uuid);

        if ($user !== null) {
            TestCase::assertNotNull($user->getDeletedAt());
        }
    }

    /**
     * @Then the field :column in the database of the user having the uuid :uuid should be :expectedValue
     */
    public function theFieldShouldBeExpectedValue(string $column, string $uuid, string $expectedValue): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $user = $this->entityManager->getRepository(User::class)->find($uuid);

        if ($user !== null) {
            TestCase::assertSame($propertyAccessor->getValue($user, $column), $expectedValue);
        }
    }
}
