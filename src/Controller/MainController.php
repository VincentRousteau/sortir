<?php

namespace App\Controller;

use App\Form\EntiteFormulaire;
use App\Form\RechercheFormType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
    public function index(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $em, ParticipantRepository $participantRepository, SortieRepository $sortieRepository): Response
    {
        $email = $authenticationUtils->getLastUsername();
        $personne = $participantRepository->findOneByEmail($email);

        $entiteFormulaire = new EntiteFormulaire();
        $form = $this->createForm(RechercheFormType::class, $entiteFormulaire);

        $form->handleRequest($request);

        if(!$form->isSubmitted()){
            $entiteFormulaire->setCampus($personne->getCampus());

            $debut = new \DateTime();
            $debut->sub(new \DateInterval('P1M'));
            $entiteFormulaire->setDateDebut($debut);

            $fin = new \DateTime();
            $fin->add(new \DateInterval("P1Y"));
            $entiteFormulaire->setDateFin($fin);
        }

        $sorties = $sortieRepository->gigaRequeteDeSesMortsDeMerde($entiteFormulaire, $personne);

        dump($entiteFormulaire);

        //$sorties=$sortieRepository->findSortieByCampus($campusUser);

//        $sortiesForm = $this->createForm(RechercheFormType::class);
//        $sortiesForm->handleRequest($request);
//
//        if ($sortiesForm->isSubmitted()) {
//
//            $campus = $sortiesForm->get('campus')->getData();
//
//            $recherche = $sortiesForm->get('recherche')->getData();
//
//            $dateDebut = $sortiesForm->get('dateDebut')->getData();
//
//            $dateFin = $sortiesForm->get('dateFin')->getData();
//
//            $sortiesOrganisees = $sortiesForm->get('sortiesOrganisees')->getData();
//            $orga = new Participant();
//            if ($sortiesOrganisees) {
//                $orga = $personne;
//            }
//
//            $inscrit = new Participant();
//            $sortiesInscrit = $sortiesForm->get('sortiesInscrit')->getData();
//            if ($sortiesInscrit) {
//                $inscrit = $personne;
//            }
//
//            $nonInscrit = new Participant();
//            $sortiesNonInscrit = $sortiesForm->get('sortiesNonInscrit')->getData();
//            if ($sortiesNonInscrit) {
//                $nonInscrit = $personne;
//            }
//
//            $etat = new Etat();
//            $sortiesPassees = $sortiesForm->get('sortiesPassees')->getData();
//            if ($sortiesPassees) {
//                $etat = $etatRepository->findOneByLibelle('Passé');
//            }
//
//            $sorties = $sortieRepository->gigaRequeteDeSesMortsDeMerde($campus, $recherche, $dateDebut, $dateFin, $orga, $inscrit, $nonInscrit, $etat);
//
//        }

        return $this->render('main/home.html.twig', [
            'personne' => $personne,
            //'sortiesForm' => $sortiesForm->createView(),
            'formulaire' => $form->createView(),
            'toutesLesSorties' => $sorties,
        ]);
    }
}