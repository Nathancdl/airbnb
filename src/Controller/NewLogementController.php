<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Entity\Logement;
use App\Form\NewLogementType;
use Doctrine\Persistence\ManagerRegistry;


class NewLogementController extends AbstractController
{
    private $security;

    public function __construct(Security $security, ManagerRegistry $managerRegistry)
    {
        $this->security = $security;
        $this->managerRegistry = $managerRegistry;
    }


    #[Route('/newlogement', name: 'app_new_logement')]
    public function creerLogement(Request $request): Response
    {
        $user = $this->security->getUser();
        

        $logement = new Logement();
        $logement->setUser($user);
        $form = $this->createForm(NewLogementType::class, $logement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->security->getUser();
            $logement->setUser($user);


            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($logement);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('new_logement/index.html.twig', [
            'form' => $form->createView(),
        ]);


    }
}
