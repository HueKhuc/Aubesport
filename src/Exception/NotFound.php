<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Uid\Uuid;

class NotFound extends Exception
{
    public function __construct(Uuid $uuid)
    {
        parent::__construct('Uuid #'.$uuid.' not found');
    }
}
