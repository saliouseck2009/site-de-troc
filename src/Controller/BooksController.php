<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookSearchType;
use App\Repository\BookRepository;
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
    public function index(Request $request, PaginatorInterface $paginator, BookRepository $repository): Response
    {
        $allBooks = $this->getDoctrine()->getRepository(Book::class)->findAll();
        $books = $paginator->paginate(
            $allBooks,//On passe les données
            $request->query->getInt('page',1),//numéro de la page en cours 1 par défaut
            9
        );

        //Traitement de la barre de recherche pricipale
        if ($request->isMethod('POST')){
            $data = $request->request->all();
        }

        //traitement de la recherche
        $searchForm = $this->createForm(BookSearchType::class);
        $searchForm->handleRequest($request);

        $donnees = $repository->findAll();

        if ($searchForm->isSubmitted() && $searchForm->isValid()){
            $title = $searchForm->getData()->getTitle();
            $donnees = $repository->searchTitle($title);

            if ($donnees == null){
                $this->addFlash('erreur','Aucun livre correspondant a votre recherche n\'a été trouvé');
            }
            $booksFound = $paginator->paginate(
                $donnees,
                $request->query->getInt('page',1),
                9
            );

            return $this->render('pages/books.html.twig', [
                'books'=>$booksFound,
                'searchForm'=>$searchForm->createView(),
            ]);
        }




        return $this->render('pages/books.html.twig', [
            'books' => $books,
            'searchForm'=>$searchForm->createView(),
            'controller_name' => 'BooksController',
            'active' => 'active',
        ]);
    }

//    /**
//     * @Route("/books/search", name="search")
//     * @param Request $request
//     * @param BookRepository $repository
//     * @param PaginatorInterface $paginator
//     * @return Response
//     */
//    public function search(Request $request, BookRepository $repository, PaginatorInterface $paginator){
//        $searchForm = $this->createForm(BookSearchType::class);
//        $searchForm->handleRequest($request);
//
//        $donnees = $repository->findAll();
//
//        if ($searchForm->isSubmitted() && $searchForm->isValid()){
//            $title = $searchForm->getData()->getTitle();
//            $donnees = $repository->searchTitle();
//
//            if ($donnees == null){
//                $this->addFlash('erreur','Aucun livre correspondant a votre recherche n\'a été trouvé');
//            }
//        }
//
//        $booksFound = $paginator->paginate(
//            $donnees,
//            $request->query->getInt('page',1),
//            9
//        );
//
//        return $this->render('pages/books.html.twig', [
//            'booksFound'=>$booksFound,
//            'searchForm'=>$searchForm->createView()
//        ]);
//
//    }
}
