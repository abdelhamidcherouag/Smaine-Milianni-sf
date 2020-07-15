<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\TokenReservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Services\ConfirmationRerservationSendler;
use App\Services\RerservationValider;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReseervationController extends AbstractController
{
    /**
     * @Route("/car/reservationCar/{id}", name="reservationCar")
     * @param EntityManagerInterface $manager
     * @param Car $car
     * @param Request $request
     * @param ConfirmationRerservationSendler $rerservationSendler
     * @param TokenReservation $tokenReservation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(EntityManagerInterface $manager,Car $car, Request $request, ConfirmationRerservationSendler $rerservationSendler)
    {
        /**
         *Permet de voir le véhicule qu'on veut réserver
         */
        $reservation = new Reservation();
        $userCar = $car->getUser();
        $user = $this->getUser();

        $form = $this->createForm(ReservationType::class, $reservation);


        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){

            $reservation = $form->getData();

            $reservation->setCars($car);
            $reservation->setUsers($userCar);
            $reservation->setUserR($user);
            $reservation->setValidad(0);

            $tokenRerservation = new TokenReservation($reservation);
            $manager->persist($tokenRerservation);
            $manager->flush();

            /**
             *envoi de mail au locataire
             */
            $rerservationSendler->sendConfirmation($car,$reservation, $tokenRerservation);
            $this->addFlash(
                'notice',
                'Un email de confirmation a ete envoyé veuillez cliquer sur le lien'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('reservation/reservation.html.twig',[
            'Car' => $car,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/car/confirmation/{Value}", name="token_reservation")
     * @param RerservationValider $rerservationValider
     * @param TokenReservation $tokenReservation
     * @param ReservationRepository $reservationRepository
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmation(RerservationValider $rerservationValider,TokenReservation $tokenReservation,ReservationRepository $reservationRepository,EntityManagerInterface $manager)
    {
        /***
         * Permet de confirmer la location
         */
        $id = $tokenReservation->getReservation();
        $reservation = $reservationRepository->find($id);
        $reservation->setValidad(1);
        $rerservationValider->sendRerservationValider($reservation);

        return  $this->redirectToRoute('home');

    }
}
