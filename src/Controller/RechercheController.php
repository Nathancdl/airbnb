<?php

namespace App\Controller;

use App\Entity\Logement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Forms;
use App\Form\RechercheType;
use Doctrine\ORM\EntityManagerInterface;

class RechercheController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/search', name: 'app_recherche')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($request);

        $logements = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $ville = $form->getData()['ville'];
            $logements = $this->entityManager->getRepository(Logement::class)->findBy(['ville' => $ville]);
        }

        return $this->render('recherche/index.html.twig', [
            'form' => $form,
            'logements' => $logements
        ]);
    }
}
