<?php

declare(strict_types=1);

namespace App\ObjectManipulation;

use App\Dto\TournamentRegistrationOutput;
use App\Entity\TournamentRegistration;

class TransferObject
{
    public function __invoke(TournamentRegistration $object, TournamentRegistrationOutput $objectDto): void
    {
        $objectDto->uuid = $object->getUuid();
        $objectDto->userUuid = $object->getUser()->getUuid();
        $objectDto->tournamentUuid = $object->getTournament()->getUuid();
        $objectDto->status = $object->getStatus();
        $objectDto->createdAt = $object->getCreatedAt();
        $objectDto->modifiedAt = $object->getModifiedAt();
        $objectDto->deletedAt = $object->getDeletedAt();
    }
}
