<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\RechercheFormType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Entity\Participant;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(AuthenticationUtils $authenticationUtils, ParticipantRepository $participantRepository, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $email = $authenticationUtils->getLastUsername();
        $personne = new Participant();
        $personne = $participantRepository->findOneByEmail($email);


        $etat = new Etat();
        $etat = $etatRepository->findOneByLibelle('Passé');

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
            'sortiesPassees'=>$sortiesPassees
        ]);
    }
}
