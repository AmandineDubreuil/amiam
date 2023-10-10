<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Page404Controller extends AbstractController
{
    #[Route('/404', name: 'app_page404')]
    public function index(): Response
    {
        return $this->render('page404/index.html.twig', [
            'controller_name' => 'Page404Controller',
        ]);
    }
}
