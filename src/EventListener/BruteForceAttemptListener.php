<?php

namespace App\EventListener;
use Anyx\LoginGateBundle\Event\BruteForceAttemptEvent;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class BruteForceAttemptListener
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $mailfrom;

    /**
     * @var string
     */
    private $mailto;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Swift_Mailer $mailer, string $mailfrom, string $mailto, Environment $twig
    )
    {
        $this->mailer = $mailer;
        $this->mailfrom = $mailfrom;
        $this->mailto = $mailto;
        $this->twig = $twig;
    }

    public function onBruteForceAttempt(BruteForceAttemptEvent $event)
    {
        $req = $event->getRequest();
        $message = (new Swift_Message())
            ->setSubject('Brute-force attack')
            ->setFrom([$this->mailfrom])
            ->setTo([$this->mailto])
            ->setBody(
                $this->twig->render('email/bruteforceattack.html.twig', [
                    'request' => $req,
                ]),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}
