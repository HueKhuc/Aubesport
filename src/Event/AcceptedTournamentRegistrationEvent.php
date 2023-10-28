<?php

declare(strict_types=1);

namespace App\Event;

class AcceptedTournamentRegistrationEvent extends TournamentRegistrationEvent
{
    public const NAME = 'tournamentRegistration.accepted';
}
