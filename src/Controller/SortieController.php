<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\RechercheVilleType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function new(Request $request, EntityManagerInterface $em, EtatRepository $etatRepository): Response
    {
        $sortie = new Sortie();
        $sortieForm  = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

            if($sortieForm->get('enregistrer')->isClicked()){
                $sortie->setEtat($etatRepository->findOneByLibelle("En creation"));
            }
            elseif($sortieForm->get('publier')->isClicked()){
                $sortie->setEtat($etatRepository->findOneByLibelle("Ouvert"));
            }

            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render("sortie/creationSortie.html.twig", [
            "sortieForm" => $sortieForm->createView()
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
    public function ajoutVille(Request $request, EntityManagerInterface $em, VilleRepository $villeRepository, SortieRepository $sortieRepository): Response
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
    public function addParticipant(int $id, SortieRepository $sortieRepository)
    {
        $user = $this->getUser();
        $sortie = $sortieRepository->find($id);

        $sortie->addParticipant($user);

        return $this->redirectToRoute('sortie_detail', [
            "id" => $id
        ]);

    }


}
