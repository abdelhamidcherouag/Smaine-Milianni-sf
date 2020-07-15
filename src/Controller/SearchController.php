<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Keyword;
use App\Form\CarType;
use App\Form\SearchCarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search_car")
     * @param Request $request
     * @param CarRepository $carRepository
     */
    public function searchCar(Request $request, CarRepository $carRepository)
    {
        $searchCarForm = $this->createForm(SearchCarType::class);
        $cars = false;
        if ($searchCarForm->handleRequest($request)->isSubmitted() && $searchCarForm->isValid()){
            $criteria = $searchCarForm->getData();

            $cars = $carRepository->searchCar($criteria);
        }
        return $this->render('search/car.html.twig',[
           'search_form' => $searchCarForm->createView(),
           'cars' => $cars
        ]);
    }
}