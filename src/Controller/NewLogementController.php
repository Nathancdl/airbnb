<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class NewLogementController extends AbstractController
{
    #[Route('/newlogement', name: 'app_new_logement')]
    public function index(Request $request): Response
    {
        return $this->render('new_logement/index.html.twig', [
            'controller_name' => 'NewLogementController',
        ]);
    }
}
