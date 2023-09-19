<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

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
