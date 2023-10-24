<?php

declare(strict_types=1);

namespace App\ObjectManipulation;

use App\Dto\UserOutput;
use App\Entity\User;

class TransferUserToOutput
{
    public function __invoke(User $user, UserOutput $userOutput): void
    {
        $userOutput->uuid = $user->getUuid();
        $userOutput->email = $user->getEmail();
        $userOutput->firstName = $user->getFirstName();
        $userOutput->lastName = $user->getLastName();
        $userOutput->pseudo = $user->getPseudo();
        $userOutput->gender = $user->getGender();
        $userOutput->birthday = $user->getBirthday();
        $userOutput->bio = $user->getBio();
        $userOutput->createdAt = $user->getCreatedAt();
        $userOutput->modifiedAt = $user->getModifiedAt();
        $userOutput->deletedAt = $user->getDeletedAt();
    }
}
