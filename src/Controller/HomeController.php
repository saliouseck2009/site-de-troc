<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findTenBook();
        $recentBook = $this->getDoctrine()->getRepository(Book::class)->findTenMostRecentBook();

        return $this->render('pages/index.html.twig', [
            'books' => $books,
            'recentbook' => $recentBook,
            'controller_name' => 'HomeController',
            'current_menu' => 'home',
            'active' => 'active',
        ]);
    }
}
