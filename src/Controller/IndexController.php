<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Keyword;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Services\ImageHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param CarRepository $carRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();

        return $this->render('index/index.html.twig',[
            'cars' => $cars,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/show/{id}", name="show")
     * @param Car $car
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Car $car)
    {

        return $this->render('home/show.html.twig',[
            'Car' => $car
        ]);
    }


    /**
     * @Route("/car/add", name="add")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param ImageHandler $imageHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(EntityManagerInterface $manager, Request $request,ImageHandler $imageHandler){


        $path = $this->getParameter('kernel.project_dir').'/public/images';
        $form = $this->createForm(CarType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $car = $form->getData();
            $user = $this->getUser();
            $car->setUser($user);

//            $manager->persist($car);
            $manager->persist($car);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Votre voiture a bien ete enregistrer '
            );
            return $this->redirectToRoute('home');
        }

        return $this->render('home/add.html.twig',[
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/car/edit/{id}", name="edit")
     * @param Car $car
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Car $car,EntityManagerInterface $manager,Request $request){

        $this->denyAccessUnlessGranted('EDIT', $car);
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $car = $form->getData();
            $manager->flush();
            $this->addFlash(
                'notice',
                'Votre voiture a bien ete modifie '
            );
            return $this->redirectToRoute('home');
        }

        return $this->render('home/edit.html.twig',[
        'Car' => $car,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/car/delete/{id}", name="delete")
     * @param Car $car
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Car $car,EntityManagerInterface $manager){

        $manager->remove($car);
        $manager->flush();

       return $this->redirectToRoute('home');
    }

    /**
     * @Route("/car/delete/keyword/{id}",
     *     name="delete_keyword",
     *     methods={"POST"},
     *     condition="request.headers.get('X-Requested-With') matches '/XMLHttpRequest/i'")
     * @param Keyword $keyword
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function deleteKeyword(Keyword $keyword, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($keyword);
        $entityManager->flush();
        return new JsonResponse();
    }



}
