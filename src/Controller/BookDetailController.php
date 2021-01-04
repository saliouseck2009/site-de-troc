<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookDetailController extends AbstractController
{
    /**
     * @Route("/book-detail", name="book_detail")
     */
    public function index(): Response
    {
        return $this->render('pages/book_detail.html.twig', [
            'controller_name' => 'BookDetailController',
            'active' => 'active'
        ]);
    }
}
