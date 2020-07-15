<?php
/**
 * Created by PhpStorm.
 * User: hamid
 * Date: 11/07/2020
 * Time: 14:48
 */

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DashboardController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard")
     */

    public function dashboard(){
        return $this->render('dashboard.html.twig',[
            'cars' => $this->getUser()->getCars(),
        ]);

    }



}