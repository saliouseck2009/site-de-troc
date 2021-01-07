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
     * @Route('/compte', name="account")
     * @param UserRepository $userRepository
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function mybooks(UserRepository $userRepository, BookRepository $bookRepository){
        return $this->render("pages/account.html.twig",[

        ]);

    }


}
