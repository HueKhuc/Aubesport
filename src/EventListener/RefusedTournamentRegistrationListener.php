<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\RefusedTournamentRegistrationEvent;
use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: RefusedTournamentRegistrationEvent::NAME)]
class RefusedTournamentRegistrationListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private Environment $twig,
        private string $adminEmail,
        private string $noReplyEmail
    ) {
    }

    public function __invoke(RefusedTournamentRegistrationEvent $refusedTournamentRegistrationEvent): void
    {
        $tournamentRegistration = $refusedTournamentRegistrationEvent->getTournamentRegistration();
        $user = $tournamentRegistration->getUser();

        $email = (new Email())
            ->from($this->noReplyEmail)
            ->to($user->getEmail())
            ->cc($this->adminEmail)
            ->subject("Inscription refusée")
            ->html($this->twig->render('refused.tournament.registration.confirmation.mail.twig', [
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName()
            ]));

        $this->mailer->send($email);
    }
}
