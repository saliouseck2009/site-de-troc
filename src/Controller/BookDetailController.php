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



    /**
     * @Route("/addbook/{id}", name="addbook")
     * @param MessageRepository $repository
     */
    public function addBook($id,EntityManagerInterface $entity, MessageRepository $messageRepository,BookRepository $bookRepository,UserRepository $userRepository, FlashyNotifier $notifier)
    {
        $book = $bookRepository->find($id);
        $user=$userRepository->find($this->getUser()->getId());
        if ($this->getUser() == null) {
            $notifier->warning('Veillez vous connecter dabort', $this->generateUrl('security_login'));
            return $this->render('security/login.html.twig');
        } else {

            if($this->isUserHaveBook($id, $user->getBooks())){
                return $this->json([
                    'message'=>"Cette oeuvre existe déjà dans votre liste de livre dans votre liste",
                    'type'=>"warn"
                ],200);
            }

            $user->addBook($book);
            $entity->flush();
            //$recentbook = $bookRepository->findTenMostRecentBook();
            return $this->json([
                'message'=>"Bravo le livre a été bien enrégistrer dans votre liste de livres",
                'type'=>"success"
            ],200);
//            return $this->render('pages/book-detail.html.twig',[
//                'book'=>$book,
//                'recentbook'=>$recentbook,]);
//            if ($users == null){
//                $book->addUser($this->getUser());
//                $notifier->success('Ajouté avec succés', '#');
//            }else{
//                $notifier->info('Ce livre existe deja dans ta liste de livre', '#');
//            }
        }
    }

    /**
     * @Route("/book/{id}", name="book")
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
        if($this->isUserHaveBook($id, $this->getUser()->getBooks())){
            return $this->json([
                'message'=>"Cette oeuvre existe déjà dans votre liste de livre dans votre liste",
                'type'=>"warn"
            ],200);
        }
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

                return $this->json([
                    'message'=>'Vous avez déja ce livre en possesion',
                    'type'=>'warn'
                ],200);
//                return $this->render('pages/book-detail.html.twig',[
//                    'recentbook'=>$recentbook,
//                    'book'=>$book,
//                ]);
            }
            else{
                $flashyNotifier->primary('Ce livre  n\' est pas encore disponible');

                return $this->json([
                    'message'=>'Ce livre  n\' est pas encore disponible',
                    'type'=>'info'
                ],200);
//                return $this->render('pages/book-detail.html.twig',[
//                    'recentbook'=>$recentbook,
//                    'book'=>$book,
//                ]);
            }


        }else{
            $flashyNotifier->warning("Veillez vous connecter dabort",$this->generateUrl('security_login'));
            return $this->render('security/login.html.twig');
        }


    }



    public function isUserHaveBook($id,$Books):bool{
        foreach ($Books as $book){
            if ($book->getId() == $id){
                return true;
            }
        }
        return false;
    }



}
