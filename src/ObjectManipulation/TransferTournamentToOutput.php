<?php

declare(strict_types=1);

namespace App\ObjectManipulation;

use App\Dto\TournamentOutput;
use App\Entity\Tournament;

class TransferTournamentToOutput
{
    public function __invoke(Tournament $tournament, TournamentOutput $tournamentOutput): void
    {
        $tournamentOutput->uuid = $tournament->getUuid();
        $tournamentOutput->name = $tournament->getName();
        $tournamentOutput->startingDate = $tournament->getStartingDate();
        $tournamentOutput->endingDate = $tournament->getEndingDate();
        $tournamentOutput->createdAt = $tournament->getCreatedAt();
        $tournamentOutput->modifiedAt = $tournament->getModifiedAt();
        $tournamentOutput->deletedAt = $tournament->getDeletedAt();
    }
}
