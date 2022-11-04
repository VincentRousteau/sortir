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

        //$personne = $participantRepository->findOneByEmail($email);

        $personne = $this->getUser();

        $sortiesParRecherche = $sortieRepository->findAll();

        $sortiesForm = $this->createForm(RechercheFormType::class);

        $sortiesForm->handleRequest($request);

        if ($sortiesForm->isSubmitted()) {

            $campus = $sortiesForm->get('campus')->getData();

            $recherche = $sortiesForm->get('recherche')->getData();

            $dateDebut = $sortiesForm->get('dateDebut')->getData();

            $dateFin = $sortiesForm->get('dateFin')->getData();

            $sortiesOrganisees = $sortiesForm->get('sortiesOrganisees')->getData();
            $orga = new Participant();
            if ($sortiesOrganisees) {
                $orga = $personne;
            }

            $inscrit = new Participant();
            $sortiesInscrit = $sortiesForm->get('sortiesInscrit')->getData();
            if ($sortiesInscrit) {
                $inscrit = $personne;
            }

            $nonInscrit = new Participant();
            $sortiesNonInscrit = $sortiesForm->get('sortiesNonInscrit')->getData();
            if ($sortiesNonInscrit) {
                $nonInscrit = $personne;
            }

            $etat = new Etat();
            $sortiesPassees = $sortiesForm->get('sortiesPassees')->getData();
            if ($sortiesPassees) {
                $etat = $etatRepository->findOneByLibelle('Passé');
            }

            $sorties = $sortieRepository->gigaRequeteDeSesMortsDeMerde($campus, $recherche, $dateDebut, $dateFin, $orga, $inscrit, $nonInscrit, $etat);

            return $this->render('main/home.html.twig', [
                'personne' => $personne,
                'sortiesForm' => $sortiesForm->createView(),
                'toutesLesSorties' => $sorties,
            ]);
        }
    }

}