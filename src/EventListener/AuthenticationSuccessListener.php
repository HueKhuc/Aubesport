<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Security;

#[AsEventListener(event: Events::JWT_CREATED)]
class AuthenticationSuccessListener
{
    public function __construct(private Security $security)
    {

    }

    public function __invoke(JWTCreatedEvent $event): void
    {
        $user = $this->security->getUser();
        assert($user instanceof User);

        $event->setData(
            array_merge(
                $event->getData(),
                [
                    'id' => $user->getUuid(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName()
                ]
            )
        );
    }
}
