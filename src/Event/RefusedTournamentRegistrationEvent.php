<?php

declare(strict_types=1);

namespace App\Event;

class RefusedTournamentRegistrationEvent extends TournamentRegistrationEvent
{
    public const NAME = 'tournamentRegistration.refused';
}
