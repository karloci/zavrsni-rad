<?php

namespace App\Module\ResetPassword\EventListener;

use App\Module\ResetPassword\Event\RequestResetPasswordEvent;
use RuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class RequestResetPasswordListener
{
    private string $clientHost;
    private MailerInterface $mailer;

    public function __construct(string $clientHost, MailerInterface $mailer)
    {
        $this->clientHost = $clientHost;
        $this->mailer = $mailer;
    }

    #[AsEventListener(event: RequestResetPasswordEvent::class)]
    public function onRequestResetPassword(RequestResetPasswordEvent $event): void
    {
        $user = $event->getUser();
        $resetPasswordToken = $event->getResetPasswordToken();

        try {
            $url = sprintf("https://%s/forgotten-password/confirm?token=%s", $this->clientHost, $resetPasswordToken->getToken());

            $email = (new Email())
                ->from("noreply@zavrsni-rad.com")
                ->to($user->getEmail())
                ->subject("Reset your password")
                ->text("Click the following link to rest your password: $url");

            $this->mailer->send($email);
        }
        catch (TransportExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }
}
