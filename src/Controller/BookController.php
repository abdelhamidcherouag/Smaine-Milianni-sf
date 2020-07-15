<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Car;
use App\Repository\BookRepository;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/Book", name="book")
     * @param BookRepository $bookRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index( BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render('book/index.html.twig',[
            'books' => $books,
        ]);
    }


    /**
     * @Route("Book/show/{id}", name="book_show")
     * @param Book $book
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Book $book)
    {
        return $this->render('book/show.html.twig',[
            'book' => $book
        ]);
    }

    /**
     * @Route("/Book/add", name="book_add")
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(EntityManagerInterface $manager){
        $book = new Book();
        $book->setTitke('livre1');
        $book->setContent('rfdsgsrfvgsdvgfs');

        $manager->persist($book);

        $manager->flush();

        return $this->render('Book/add.html.twig');
    }

    /**
     * @Route("/Book/edit/{id}", name="book_edit")
     * @param Book $book
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Book $book,EntityManagerInterface $manager){

        $book->setModel("harry");
        $manager->flush($book);
        return $this->render('Book/edit.html.twig',[
            'Book' => $book
        ]);
    }

    /**
     * @Route("/Book/delete/{id}", name="book_delete")
     * @param Book $book
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Book $book, EntityManagerInterface $manager){

        $manager->remove($book);
        $manager->flush();

        return $this->redirectToRoute('home');
    }


}
