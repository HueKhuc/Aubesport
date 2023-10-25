<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\TournamentRegistration;
use Symfony\Contracts\EventDispatcher\Event;

class AcceptedTournamentRegistrationEvent extends Event
{
    public const NAME = 'tournamentRegistration.accepted';

    public function __construct(
        protected TournamentRegistration $tournamentRegistration,
    ) {
    }

    public function getTournamentRegistration(): TournamentRegistration
    {
        return $this->tournamentRegistration;
    }
}
