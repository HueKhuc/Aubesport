<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use PHPUnit\Framework\TestCase;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\HttpFoundation\Request;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Symfony\Component\HttpFoundation\Response;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Defines application features from the specific context.
 */
class UserContext implements Context
{
    /**
     * @AfterScenario
     */
    public function cleanUpUsers(AfterScenarioScope $event): void
    {
        exec('bin/console doctrine:query:sql "DELETE FROM user"');
    }
}
