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

//        $sortiesOrganisees = $sortieRepository->findAllSortiesOrganisees($personne);
//
//        $sortiesParticipees = $sortieRepository->findAllSortiesInscrites($personne);
//
//        $sortiesNonParticipees = $sortieRepository->findAllSortiesNonInscrites($personne);
//
//        $sortiesPassees = $sortieRepository->findAllSortiesPassees($etat);
//
//        $sortiesParRecherche = $sortieRepository->findAll();

        $sortiesForm = $this->createForm(RechercheFormType::class);

        $sortiesForm->handleRequest($request);

        if ($sortiesForm->isSubmitted()) {

            $campus = $sortiesForm->get('campus')->getData();
            dump($campus);

            $recherche = $sortiesForm->get('recherche')->getData();
            dump($recherche);

            $dateDebut = $sortiesForm->get('dateDebut')->getData();
            dump($dateDebut);

            $dateFin = $sortiesForm->get('dateFin')->getData();
            dump($dateFin);

            $sortiesOrganisees = $sortiesForm->get('sortiesOrganisees')->getData();
            dump($sortiesOrganisees);
            $orga=new Participant();
            if ( $sortiesOrganisees){
                $orga=$personne;
            }

            $inscrit=new Participant();
            $sortiesInscrit = $sortiesForm->get('sortiesInscrit')->getData();
            dump($sortiesInscrit);
            if ($sortiesInscrit){
                $inscrit=$personne;
            }

            $nonInscrit=new Participant();
            $sortiesNonInscrit = $sortiesForm->get('sortiesNonInscrit')->getData();
            dump($sortiesNonInscrit);
            if ($sortiesNonInscrit){
                $nonInscrit=$personne;
            }

            $etat=new Etat();
            $sortiesPassees = $sortiesForm->get('sortiesPassees')->getData();
            dump($sortiesPassees);
            if ($sortiesPassees){
                $etat = $etatRepository->findOneByLibelle('Passé');
            }

            dump($orga);
            dump($inscrit);
            dump($nonInscrit);
            dump($etat);

            $sorties = $sortieRepository->gigaRequeteDeSesMortsDeMerde($campus, $recherche, $dateDebut, $dateFin, $orga, $inscrit, $nonInscrit, $etat);

//            if (is_null($recherche)) {
//                $sortiesParRecherche = $sortieRepository->findAll();
//            } else {
//                $sortiesParRecherche = $sortieRepository->findAllSortiesParRecherche($recherche);
//            }
        }


        return $this->render('main/home.html.twig', [
            'personne' => $personne,
            'sortiesForm' => $sortiesForm->createView(),
            'toutesLesSorties'=>$sorties,
//            'sortiesOrganisees' => $sortiesOrganisees,
//            'sortiesParticipees' => $sortiesParticipees,
//            'sortiesNonParticipees' => $sortiesNonParticipees,
//            'sortiesPassees' => $sortiesPassees,
//            'sortiesParRecherche' => $sortiesParRecherche
        ]);
    }
}
