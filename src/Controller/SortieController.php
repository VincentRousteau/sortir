<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AnnulerSortie;
use App\Form\LieuType;
use App\Form\RechercheVilleType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie/{id}', name: 'sortie_detail', requirements: ['id' => '\d+'])]
    public function index(SortieRepository $sortieRepository, int $id): Response
    {

        $sortie = $sortieRepository->find($id);

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/sortie/creation', name:"sortie_creation")]
    public function new(Request $request, EntityManagerInterface $em, EtatRepository $etatRepository, VilleRepository $villeRepository): Response
    {
        $sortie = new Sortie();
        $sortieForm  = $this->createForm(SortieType::class, $sortie);
        $villes = $villeRepository->findAll();

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

            if($sortieForm->get('enregistrer')->isClicked()){
                $sortie->setEtat($etatRepository->findOneByLibelle("En creation"));
            }
            elseif($sortieForm->get('publier')->isClicked()){
                $sortie->setEtat($etatRepository->findOneByLibelle("Ouvert"));
                $sortie->addParticipant($this->getUser());
            }
            $sortie->setOrganisateur($this->getUser());
            $sortie->getLieu()->setVille($sortieForm->get('ville')->getData());

            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render("sortie/creationSortie.html.twig", [
            "sortieForm" => $sortieForm->createView(),
            "listeVilles" => $villes
        ]);
    }

    #[Route('/sortie/ajoutLieu', name:"lieu_creation")]
    public function ajoutLieu(Request $request, EntityManagerInterface $em): Response
    {
        $lieu = new Lieu();
        $lieuForm  = $this->createForm(LieuType::class, $lieu);

        $lieuForm->handleRequest($request);

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
            $em->persist($lieu);
            $em->flush();
            return $this->redirectToRoute('sortie_creation');
        }

        return $this->render("sortie/creationLieu.html.twig", [
            "lieuForm" => $lieuForm->createView()
        ]);
    }

    #[Route('/sortie/ajoutVille', name:"ville_creation")]
    #[IsGranted('ROLE_ADMIN')]
    public function ajoutVille(Request $request, EntityManagerInterface $em, VilleRepository $villeRepository): Response
    {

        $ville = new Ville();
        $villeForm  = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){
            $em->persist($ville);
            $em->flush();
        }

        $rechercheVilleForm = $this->createForm(RechercheVilleType::class);
        $rechercheVilleForm->handleRequest(($request));

        $villesParRecherche = $villeRepository->findAll();

        if ($rechercheVilleForm->isSubmitted()) {

            $recherche = $rechercheVilleForm->get('motCle')->getData();
            dump($recherche);

            if (!is_null($recherche)) {
                $villesParRecherche = $villeRepository->findAllCitiesParRecherche($recherche);
            }
        }


        return $this->render("sortie/creationVille.html.twig", [
            "rechecherVilleForm" => $rechercheVilleForm->createView(),
            "villesParRecherche" => $villesParRecherche,
            "villeForm" => $villeForm->createView()
        ]);
    }

    #[Route('/sortie/addparticipant/{id}', name:"addParticipant")]
    public function addParticipant(int $id, SortieRepository $sortieRepository, EtatRepository $stateRepository, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $sortie = $sortieRepository->find($id);


        $user->addSortie($sortie);
        $em->persist($user);

        $sortie->addParticipant($user);

        if($sortie->getNbInscriptionsMax() <= count($sortie->getParticipants())){
            $sortie->setEtat($stateRepository->findOneByLibelle("ferm??"));
        }

        $em->persist($sortie);
        $em->flush();

        return $this->redirectToRoute('sortie_detail', [
            "id" => $id
        ]);

    }

    #[Route('/sortie/removeparticipant/{id}', name:"removeParticipant")]
    public function removeParticipant(int $id, SortieRepository $sortieRepository, EntityManagerInterface $em, EtatRepository $stateRepository)
    {
        $user = $this->getUser();
        $sortie = $sortieRepository->find($id);


        $user->removeSortie($sortie);
        $em->persist($user);

        $sortie->removeParticipant($user);

        if("now" <  $sortie->getDateLimiteInscription()){
            $sortie->setEtat($stateRepository->findOneByLibelle("ouvert"));
        }

        $em->persist($sortie);
        $em->flush();

        return $this->redirectToRoute('sortie_detail', [
            "id" => $id
        ]);
    }

    #[Route('/sortie/publish/{id}', name:"publish_sortie")]
    public function publish(int $id, SortieRepository $sortieRepository, EntityManagerInterface $em, EtatRepository $stateRepository)
    {
        $sortie = $sortieRepository->find($id);
        $sortie->setEtat($stateRepository->findOneByLibelle("ouvert"));
        $sortie->addParticipant($this->getUser());
        $em->persist($sortie);
        $em->flush();

        return $this->redirectToRoute('homepage', [
            "id" => $id
        ]);
    }

    #[Route('/sortie/cancel/{id}', name:"cancel_sortie")]
    public function cancel(int $id, SortieRepository $sortieRepository, EntityManagerInterface $em, EtatRepository $stateRepository, Request $request)
    {
        $sortie = $sortieRepository->find($id);

        $annnulerForm  = $this->createForm(AnnulerSortie::class);
        $annnulerForm->handleRequest($request);

        if($annnulerForm->isSubmitted() && $annnulerForm->isValid()){

            $texte=$sortie->getInfosSortie()." ANNUL?? : ".$annnulerForm->get("raison")->getData();
            $sortie->setInfosSortie($texte);
            $sortie->setEtat($stateRepository->findOneByLibelle("historis??"));

            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render("sortie/annulationSortie.html.twig", [
            "annulerForm" => $annnulerForm->createView(),
        ]);
    }

    #[Route('/sortie/modificationSortie/{id}', name:"modifier_sortie", requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function modify_sortie(int $id, SortieRepository $sortieRepository, EntityManagerInterface $em, VilleRepository $villeRepository, Request $request, EtatRepository $etatRepository):Response
    {
        $sortie = $sortieRepository->find($id);
        $sortieForm  = $this->createForm(SortieType::class, $sortie);
        $villes = $villeRepository->findAll();

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

            if($sortieForm->get('enregistrer')->isClicked()){
                $sortie->setEtat($etatRepository->findOneByLibelle("En creation"));
            }
            elseif($sortieForm->get('publier')->isClicked()){
                $sortie->setEtat($etatRepository->findOneByLibelle("Ouvert"));
                $sortie->addParticipant($this->getUser());
            }
            $sortie->setOrganisateur($this->getUser());
            $lieu = $sortie->getLieu();
            $sortie->getLieu()->setVille($sortieForm->get('ville')->getData());

            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render("sortie/modificationSortie.html.twig", [
            "sortieForm" => $sortieForm->createView(),
            "listeVilles" => $villes
        ]);
    }

//    #[Route('/sortie/AnnulerSortie/{id}', name:"annuler_sortie", requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
//    public function annuler_sortie(int $id, SortieRepository $sortieRepository, EntityManagerInterface $em, VilleRepository $villeRepository, Request $request, EtatRepository $etatRepository):Response
//    {
//
//    }




}
