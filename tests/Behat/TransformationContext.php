<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;

class TransformationContext implements Context
{
    /**
     * @Transform /^NULL$/i
     */
    public function transformNull(): mixed
    {
        return null;
    }

    /**
     * @Transform /^".*"$/
     */
    public function transformString(string $string): string
    {
        return $string;
    }

    /**
     * @Transform /^\d+$/
     */
    public function transformInt(int $int): int
    {
        return $int;
    }
}
