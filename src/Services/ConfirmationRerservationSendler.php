<?php

namespace App\Services;


use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\TokenReservation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

class ConfirmationRerservationSendler {


    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer ,Environment $twig, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;

    }


    public function sendConfirmation(Car $car,Reservation $reservation,TokenReservation $tokenReservation){

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('noreply@rent-car.com')
            ->setTo($car->getUser()->getEmail())
            ->setBody(
                $this->twig->render(
                // templates/emails/registration.html.twig
                    'emails/confirmationRerservation.html.twig',
                    ['token' => $tokenReservation->getValue(),
                      'reservation' => $reservation
                    ]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}