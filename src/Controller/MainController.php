<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\RechercheFormType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Entity\Participant;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
    public function index(ParticipantRepository $participantRepository, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $email = $this->getUser()->getUserIdentifier();

        $personne = $participantRepository->findOneByEmail($email);

        $etat = $etatRepository->findOneByLibelle('Passé');

        $sortiesParRecherche = $sortieRepository->findAllSortiesParRecherche("cinema");

        $sortiesOrganisees = $sortieRepository->findAllSortiesOrganisees($personne);

        $sortiesParticipees = $sortieRepository->findAllSortiesInscrites($personne);

        $sortiesNonParticipees = $sortieRepository->findAllSortiesNonInscrites($personne);

        $sortiesPassees = $sortieRepository->findAllSortiesPassees($etat);

        $sortiesForm = $this->createForm(RechercheFormType::class);

        return $this->render('main/home.html.twig', [
            'personne' => $personne,
            'sortiesForm' => $sortiesForm->createView(),
            'sortiesOrganisees' => $sortiesOrganisees,
            'sortiesParticipees' => $sortiesParticipees,
            'sortiesNonParticipees' => $sortiesNonParticipees,
            'sortiesPassees'=>$sortiesPassees,
            'sortiesParRecherche'=>$sortiesParRecherche
        ]);
    }
}
