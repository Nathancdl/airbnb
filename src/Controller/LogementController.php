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

class LogementController extends AbstractController
{

    private $security;
    private $entityManager;


    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/logement/{id}', name: 'app_logement')]
    public function index($id, Request $request): Response
    {
        $logement = $this->entityManager->getRepository(Logement::class)->find($id);
        $user = $this->security->getUser();

        if($user){
            $reservation = new Reservation();
            $form = $this->createForm(NewReservationType::class, $reservation);
            $form->handleRequest($request);
            
        

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les dates de début et de fin
            $dateDebut = $form->get('DateDebut')->getData();
            $dateFin = $form->get('DateFin')->getData();
        }
        
        
        }
        // Vérifiez si le logement existe
        if (!$logement) {
            throw $this->createNotFoundException('Logement non trouvé');
        }


        return $this->render('logement/index.html.twig', [
            'logement' => $logement,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
