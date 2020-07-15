<?php

namespace App\Services;

use App\Entity\Token;
use App\Entity\User;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

class TokenSendler {


    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer ,Environment $twig, ContainerInterface $container)
    {
       $this->mailer = $mailer;
       $this->twig = $twig;
    }


    public function sendToken(User $user, Token $token){

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('noreply@rent-car.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['token' => $token->getValue()]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}