<?php

namespace App\Module\VerifyEmail\EventListener;

use App\Module\VerifyEmail\Event\RequestEmailVerificationEvent;
use RuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class RequestEmailVerificationListener
{
    private string $clientHost;
    private MailerInterface $mailer;

    public function __construct(string $clientHost, MailerInterface $mailer)
    {
        $this->clientHost = $clientHost;
        $this->mailer = $mailer;
    }

    #[AsEventListener(event: RequestEmailVerificationEvent::class)]
    public function onRequestEmailVerification(RequestEmailVerificationEvent $event): void
    {
        $user = $event->getUser();
        $verifyEmailToken = $event->getVerifyEmailToken();

        try {
            $url = sprintf("https://%s/verify-email?token=%s", $this->clientHost, $verifyEmailToken->getToken());

            $email = (new Email())
                ->from("noreply@zavrsni-rad.com")
                ->to($user->getEmail())
                ->subject("Verify your email address")
                ->text("Click the following link to verify your email address: $url");

            $this->mailer->send($email);
        }
        catch (TransportExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }
}
