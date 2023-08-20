<?php

namespace App\Service;

use App\Service\MessageGenerator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailAfterReservation
{
    public function __construct(
        private MessageGenerator $messageGenerator,
        private MailerInterface $mailer,

    ) {
    }

    public function notifyOfSiteUpdate(): bool
    {
        $happyMessage = $this->messageGenerator->getHappyMessage();

        $email = (new Email())
            ->from('bogdanbobosko0@gmail.com')
            ->to('boghdan35423475@gmail.com')
            ->subject('one more service was ordered!')
            ->text('Someone just ordered a service. We told him: '.$happyMessage);

        $this->mailer->send($email);

        return true;
    }
}

