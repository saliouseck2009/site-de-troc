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
        $demandBooks = array();

        if ($usersMessages){
            foreach ($usersMessages as $message ){
                $commandBooks[]= $bookRepository->find($message->getMessageText());
                //dump($user->getEmail());
            }
            foreach ($messages as $message){
                if($message->getRecipient() == $user->getEmail()){
                    $demandBooks[] = $bookRepository->find($message->getMessageText());
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
            ]);
        }else{
            $flashyNotifier->warning("Veillez vous connecter dabort",$this->generateUrl('security_login'));
            return $this->render('security/login.html.twig');
        }
    }


}
