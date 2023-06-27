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
    private $form;
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
    
        if (!$logement) {
            throw $this->createNotFoundException('Logement non trouvé');
        }
        
        $reservation = new Reservation();
        $form = $this->createForm(NewReservationType::class, $reservation);
        if($user){

            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()){


                $dateDebut = $form->get('DateDebut')->getData();
                $dateFin = $form->get('DateFin')->getData();

                $isDisponible = $this->isDatesDisponibles($id, $dateDebut, $dateFin);

                if($isDisponible){
                    $reservation = new Reservation();
                    $reservation->setLogement($logement);
                    $reservation->setUser($user);
                    $reservation->setDateDebut($dateDebut);
                    $reservation->setDateFin($dateFin);

                    // Enregistrement dans la base de données
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

        // Vérification des dates de réservation
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

    #[Route('/logement/ajouter-favori/{id}', name: 'app_logement_ajouter_favori')]
    public function ajouterFavori(Logement $logement, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $user->addFavori($logement);
        $entityManager->flush();

        return $this->redirectToRoute('app_logement', ['id' => $logement->getId()]);
    }

    #[Route('/logement/retirer-favori/{id}', name: 'app_logement_retirer_favori')]
    public function retirerFavori(Logement $logement, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $user->removeFavori($logement);
        $entityManager->flush();

        return $this->redirectToRoute('app_logement', ['id' => $logement->getId()]);
    }
}
