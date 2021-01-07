<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookDetailController extends AbstractController
{
    /**
     * @Route("/book{id}", name="book")
     */
    public function index($id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);
        return $this->render('pages/book_detail.html.twig', [
            'book'=>$book,
        ]);
    }

    /**
     * @Route("/book/{id}", name="book")
     * @param MessageRepository $repository
     */
    public function addBook($id,EntityManagerInterface $entity, MessageRepository $messageRepository,BookRepository $bookRepository,UserRepository $userRepository, FlashyNotifier $notifier)
    {
        $book = $bookRepository->find($id);
        if ($this->getUser() == null) {
            $notifier->warning('Veillez vous connecter dabort', $this->generateUrl('security_login'));
            return $this->render('pages/book-detail.html.twig');
        } else {
            $book->addUser($this->getUser());
            $entity->persist($book);
            $entity->flush();
            return $this->render('pages/book-detail.html.twig');
//            if ($users == null){
//                $book->addUser($this->getUser());
//                $notifier->success('Ajouté avec succés', '#');
//            }else{
//                $notifier->info('Ce livre existe deja dans ta liste de livre', '#');
//            }
        }
    }

    /**
     * @Route("/addbook/{id}", name="addbook")
     * @param MessageRepository $repository
     */
    public function Book($id,EntityManagerInterface $entity, MessageRepository $messageRepository,BookRepository $bookRepository,UserRepository $userRepository, FlashyNotifier $notifier)
    {
        $book = $bookRepository->find($id);
        $recentbook = $bookRepository->findTenMostRecentBook();


        return $this->render('pages/book-detail.html.twig',[
            'recentbook'=>$recentbook,
            'book'=>$book,
        ]);
    }
}
