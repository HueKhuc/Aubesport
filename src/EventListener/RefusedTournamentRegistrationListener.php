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
        private Environment $twig
    ) {
    }

    public function __invoke(RefusedTournamentRegistrationEvent $refusedTournamentRegistrationEvent): void
    {
        $tournamentRegistration = $refusedTournamentRegistrationEvent->getTournamentRegistration();
        $user = $tournamentRegistration->getUser();

        $email = (new Email())
            ->from('noreply@aubesport.fr')
            ->to($user->getEmail())
            ->cc('admin@aubesport.fr')
            ->subject("Inscription refusée")
            ->html($this->twig->render('refused.tournament.registration.confirmation.mail.twig', [
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName()
            ]));

        $this->mailer->send($email);
    }
}
