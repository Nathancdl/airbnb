<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Logement;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Form\NewReservationType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;

class LogementController extends AbstractController
{

    private $security;
    private $entityManager;
    private $reservationRepository;


    public function __construct(ManagerRegistry $managerRegistry, ReservationRepository $reservationRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/logement/{id}', name: 'app_logement')]
    public function index($id, Request $request): Response
    {
        $displayIndisponible = false;
        $logement = $this->entityManager->getRepository(Logement::class)->find($id);
        $user = $this->security->getUser();
    



        // Vérifiez si le logement existe
        if (!$logement) {
            throw $this->createNotFoundException('Logement non trouvé');
        }
        

        if($user){
            $reservation = new Reservation();
            $form = $this->createForm(NewReservationType::class, $reservation);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()){

                // Récupérer les dates de début et de fin
                $dateDebut = $form->get('DateDebut')->getData();
                $dateFin = $form->get('DateFin')->getData();

                $isDisponible = $this->isDatesDisponibles($id, $dateDebut, $dateFin);

                if($isDisponible){
                    $reservation = new Reservation();
                    $reservation->setLogement($logement);
                    $reservation->setUser($user);
                    $reservation->setDateDebut($dateDebut);
                    $reservation->setDateFin($dateFin);
                    // Enregistrez le logement dans la base de données
                    $entityManager = $this->managerRegistry->getManager();
                    $entityManager->persist($reservation);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_reservation_succes');
                    
                }else{
                    $displayIndisponible = true;
                }
            }
        }

        return $this->render('logement/index.html.twig', [
            'logement' => $logement,
            'user' => $user,
            'form' => $form,
            'displayIndisponible' => $displayIndisponible
        ]);
    }

    private function isDatesDisponibles(int $logementId, \DateTime $dateDebut, \DateTime $dateFin): bool
    {
        $repository = $this->entityManager->getRepository(Reservation::class);

        // Vérification de la disponibilité des dates de réservation
        $reservations = $repository->createQueryBuilder('r')
            ->where('r.Logement = :logementId')
            ->andWhere('r.DateDebut <= :dateFin')
            ->andWhere('r.DateFin >= :dateDebut')
            ->setParameter('logementId', $logementId)
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->getQuery()
            ->getResult();

        return count($reservations) === 0;
    }
}
