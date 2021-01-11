<?php

namespace App\Controller;

use App\Entity\Message;
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
//    /**
//     * @Route("/book{id}", name="book")
//     */
//    public function index($id, BookRepository $bookRepository): Response
//    {
//        $book = $bookRepository->find($id);
//        return $this->render('pages/book_detail.html.twig', [
//            'book'=>$book,
//        ]);
//    }

    /**
     * @Route("/book/{id}", name="book")
     * @param MessageRepository $repository
     */
    public function addBook($id,EntityManagerInterface $entity, MessageRepository $messageRepository,BookRepository $bookRepository,UserRepository $userRepository, FlashyNotifier $notifier)
    {
        $book = $bookRepository->find($id);
        if ($this->getUser() == null) {
            $notifier->warning('Veillez vous connecter dabort', $this->generateUrl('security_login'));
            return $this->render('security/login.html.twig');
        } else {
            $book->addUser($this->getUser());
            $entity->persist($book);
            $entity->flush();
            $recentbook = $bookRepository->findTenMostRecentBook();
            return $this->render('pages/book-detail.html.twig',[
                'book'=>$book,
                'recentbook'=>$recentbook,]);
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

    /**
     * @Route("/askbook/{id}", name="askbook")
     * @param BookRepository $bookRepository
     * @param UserRepository $userRepository
     */
    public function askBook($id, BookRepository $bookRepository,UserRepository $userRepository, MessageRepository $messageRepository, FlashyNotifier $flashyNotifier, EntityManagerInterface $entityManager){
        $book = $bookRepository->find($id);
        $recentbook = $bookRepository->findTenMostRecentBook();
        if ($this->getUser()){
            $users = $userRepository->findUserBook($id);
            if($users) {
                foreach ($users as $user){
                    if ($user !=$this->getUser()) {
                        $message = new Message;
                        $message->setUser($this->getUser())
                            ->setCreatedAt(new \DateTime())
                            ->setMessageText($id)
                            ->setRecipient($user->getEmail())
                            ->setType('Demande')
                            ->setValidate(false);
                        $entityManager->persist($message);
                        $entityManager->flush();
                        $flashyNotifier->primary('Votre demande a été bien enregistré et est en traitement');

                    }else{
                        $flashyNotifier->primary('Vous avez déja ce livre en possesion');

                    }
                }

                return $this->render('pages/book-detail.html.twig',[
                    'recentbook'=>$recentbook,
                    'book'=>$book,
                ]);
            }
            else{
                $flashyNotifier->primary('Ce livre  n\' est pas encore disponible');
                return $this->render('pages/book-detail.html.twig',[
                    'recentbook'=>$recentbook,
                    'book'=>$book,
                ]);
            }


        }else{
            $flashyNotifier->warning("Veillez vous connecter dabort",$this->generateUrl('security_login'));
            return $this->render('security/login.html.twig');
        }


    }

}
