<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationSuccesController extends AbstractController
{
    #[Route('/reservation/succes', name: 'app_reservation_succes')]
    public function index(): Response
    {
        return $this->render('reservation_succes/index.html.twig', [
            'controller_name' => 'ReservationSuccesController',
        ]);
    }
}
