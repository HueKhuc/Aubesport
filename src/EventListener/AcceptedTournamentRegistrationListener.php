<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\AcceptedTournamentRegistrationEvent;
use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: AcceptedTournamentRegistrationEvent::NAME)]
class AcceptedTournamentRegistrationListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private Environment $twig
    ) {
    }

    public function __invoke(AcceptedTournamentRegistrationEvent $acceptedTournamentRegistrationEvent): void
    {
        $tournamentRegistration = $acceptedTournamentRegistrationEvent->getTournamentRegistration();
        $user = $tournamentRegistration->getUser();

        $email = (new Email())
            ->from('noreply@aubesport.fr')
            ->to($user->getEmail())
            ->cc('admin@aubesport.fr')
            ->subject("Inscription acceptée")
            ->html($this->twig->render('accepted.tournament.registration.confirmation.mail.twig', [
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName()
            ]));

        $this->mailer->send($email);
    }
}
