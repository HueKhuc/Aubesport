<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\User;
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
     * @Given there is an existant user with email :email
     */
    public function aUserWithEmail(string $email): void
    {
        $user = new User();
        $user->setEmail($email);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
