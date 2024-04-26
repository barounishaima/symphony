<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('demo/index.html.twig', [
            'controller_name' => 'DemoController',
        ]);
    }
    #[Route('/{username}', name: 'user_name')]

    public function index2($username): Response
    {
        return $this->render('demo/index2.html.twig', [
            'nom' => $username,
        ]);
    }
    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return $this->render('demo/test.html.twig', [
            'title' => 'Les amies',
            'age' => 13,
        ]);
    }
    
}
