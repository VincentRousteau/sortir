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
use function PHPUnit\Framework\isNull;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
    public function index(Request $request, ParticipantRepository $participantRepository, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $email = $this->getUser()->getUserIdentifier();

        $personne = $participantRepository->findOneByEmail($email);

        $etat = $etatRepository->findOneByLibelle('Passé');

        $sortiesOrganisees = $sortieRepository->findAllSortiesOrganisees($personne);

        $sortiesParticipees = $sortieRepository->findAllSortiesInscrites($personne);

        $sortiesNonParticipees = $sortieRepository->findAllSortiesNonInscrites($personne);

        $sortiesPassees = $sortieRepository->findAllSortiesPassees($etat);

        $sortiesParRecherche = $sortieRepository->findAll();

        $sortiesForm = $this->createForm(RechercheFormType::class);

        $sortiesForm->handleRequest($request);

        if ($sortiesForm->isSubmitted()) {

            $recherche = $sortiesForm->get('recherche')->getData();
            dump($recherche);

            if (is_null($recherche)) {
                $sortiesParRecherche = $sortieRepository->findAll();
            } else {
                $sortiesParRecherche = $sortieRepository->findAllSortiesParRecherche($recherche);
            }
        }


        return $this->render('main/home.html.twig', [
            'personne' => $personne,
            'sortiesForm' => $sortiesForm->createView(),
            'sortiesOrganisees' => $sortiesOrganisees,
            'sortiesParticipees' => $sortiesParticipees,
            'sortiesNonParticipees' => $sortiesNonParticipees,
            'sortiesPassees' => $sortiesPassees,
            'sortiesParRecherche' => $sortiesParRecherche
        ]);
    }
}
