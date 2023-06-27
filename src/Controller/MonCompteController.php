<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class MonCompteController extends AbstractController
{
    private $security;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/mon/compte', name: 'app_mon_compte')]
    public function index(): Response
    {
        $user = $this->security->getUser();

        $reservations = $this->entityManager->getRepository(Reservation::class)->findBy(['User' => $user]);

        return $this->render('mon_compte/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}
