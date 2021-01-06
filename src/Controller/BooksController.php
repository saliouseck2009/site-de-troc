<?php

namespace App\Controller;

use App\Entity\Book;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    /**
     * @Route("/books", name="books")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $allBooks = $this->getDoctrine()->getRepository(Book::class)->findAll();
        $books = $paginator->paginate(
            $allBooks,//On passe les données
            $request->query->getInt('page',1),//numéro de la page en cours 1 par défaut
            9
        );
        return $this->render('pages/books.html.twig', [
            'books' => $books,
            'controller_name' => 'BooksController',
            'active' => 'active',
        ]);
    }


}
