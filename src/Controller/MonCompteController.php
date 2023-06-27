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

    #[Route('/moncompte', name: 'app_mon_compte')]
    public function index(): Response
    {
        $user = $this->security->getUser();

        $myreservations = $this->entityManager->getRepository(Reservation::class)->findBy(['User' => $user]);

        $logements = $user->getLogements();

        $reservations = [];

        foreach ($logements as $logement) {
            $reservationsLogement = $this->entityManager->getRepository(Reservation::class)->findBy(['Logement' => $logement]);
            $reservations[$logement->getId()] = $reservationsLogement;
        }

        return $this->render('mon_compte/index.html.twig', [
            'myreservations' => $myreservations,
            'logements' => $logements,
            'reservations' => $reservations
        ]);
    }
}
