<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonCompteController extends AbstractController
{
//    /**
//     * @Route("/account", name="account")
//     */
//    public function index(): Response
//    {
//        $user=$this->getUser();
//        return $this->render('pages/account.html.twig', [
//            'controller_name' => 'MonCompteController',
//        ]);
//    }

    /**
     * @Route("/compte", name="account")
     * @param UserRepository $userRepository
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function mybooks(UserRepository $userRepository,MessageRepository $messageRepository, BookRepository $bookRepository,FlashyNotifier $flashyNotifier){
        /** @var \App\Entity\User $user */
        $user=$userRepository->find($this->getUser()->getId());
        $usersMessages = $this->getUser()->getMessages();
        $messages = $messageRepository->findAll();
        $commandBooks = array();
        $commandBookMessage =array();
        $demandBooks = array();
        $demandBookMessages = array();

        if ($usersMessages){
            foreach ($usersMessages as $message ){
                $commandBooks[]= $bookRepository->find($message->getMessageText());
                $commandBookMessage[]=$message;
                //dump($user->getEmail());
            }
            foreach ($messages as $message){
                if($message->getRecipient() == $user->getEmail()){
                    $demandBooks[] = $bookRepository->find($message->getMessageText());
                    $demandBookMessages[]=$message;
                }
            }
        }
        if ($this->getUser()){
            //$orderedbooks = $bookRepository->find();
            //dd($user);
            return $this->render("pages/account.html.twig",[
                'user'=>$user,
                'commandBooks'=>$commandBooks,
                'demandBooks'=>$demandBooks,
                'commandBookMessage'=>$commandBookMessage,
                'demandBookMessages'=>$demandBookMessages,
            ]);
        }else{
            $flashyNotifier->warning("Veillez vous connecter dabort",$this->generateUrl('security_login'));
            return $this->render('security/login.html.twig');
        }
    }

    /**
     * @Route("/book/delete/{id}", name="deleteUserBook")
     * @param BookRepository $bookRepository
     */
    public function deleteUserBook($id, BookRepository $bookRepository, EntityManagerInterface $entityManager,FlashyNotifier $flashyNotifier){
        $book=$bookRepository->find($id);
        $entityManager->remove($book);
        $entityManager->flush();
        $flashyNotifier->success('Le livre a été supprimé de vos livre avec succes');
        return $this->redirectToRoute("account");


    }

    /**
     * @Route("/message/delete/{idMessage}",name="deleteMessage")
     * @param $idMessage
     */
    public function annulerCommande($idMessage, MessageRepository $messageRepository, EntityManagerInterface $entityManager,FlashyNotifier $flashyNotifier){
        $message = $messageRepository->find($idMessage);
        $entityManager->remove($message);
        $entityManager->flush();
        $flashyNotifier->success('Le livre a été supprimé de vos livre avec succes');
        return $this->redirectToRoute("account");

    }

    /**
     * @Route("/commande/validate/{idbook}/{iduser}/{idmessage}", name="validateCommande")
     * @param $idbook
     * @param BookRepository $bookRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function validateCommande($idbook,$iduser ,$idmessage,BookRepository $bookRepository,FlashyNotifier $flashyNotifier, UserRepository $userRepository,MessageRepository $messageRepository, EntityManagerInterface $entityManager){
        $receiver = $userRepository->find($iduser);
        $sender = $userRepository->find($this->getUser()->getId());
        $message = $messageRepository->find($idmessage);
        $book = $bookRepository->find($idbook);
        $sender->removeBook($book);
        $sender->setPoints($sender->getPoints()+2);
        $receiver->addBook($book);
        $receiver->setPoints($receiver->getPoints()-2);
        $entityManager->remove($message);
        $entityManager->flush();
        $flashyNotifier->message("Veillez s'il vous plait envoyer le livre dans les plus bref delai");
        return $this->redirectToRoute("account");

    }


    /**
     * @Route("/commande/refuse/{idmessage}", name="refuseCommande")
     * @param $idmessage
     * @param BookRepository $bookRepository
     * @param FlashyNotifier $flashyNotifier
     * @param UserRepository $userRepository
     * @param MessageRepository $messageRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseCommande($idmessage,BookRepository $bookRepository,FlashyNotifier $flashyNotifier, UserRepository $userRepository,MessageRepository $messageRepository, EntityManagerInterface $entityManager){
        $message = $messageRepository->find($idmessage);
        $entityManager->remove($message);
        $entityManager->flush();
        $flashyNotifier->message("Votre Réponse a été bien envoyer. Merci!");
        return $this->redirectToRoute("account");

    }


}
