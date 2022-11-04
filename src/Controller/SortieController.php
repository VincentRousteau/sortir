<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\RechercheFormType;
use App\Form\SortieType;
use App\Form\VilleType;
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
    public function new(Request $request, EntityManagerInterface $em)
    {
        $sortie = new Sortie();
        $sortieForm  = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render("sortie/creationSortie.html.twig", [
            "sortieForm" => $sortieForm->createView()
        ]);
    }

    #[Route('/sortie/ajoutLieu', name:"lieu_creation")]
    public function ajoutLieu(Request $request, EntityManagerInterface $em)
    {
        $lieu = new Lieu();
        $lieuForme  = $this->createForm(LieuType::class, $lieu);

        $lieuForme->handleRequest($request);

        if($lieuForme->isSubmitted() && $lieuForme->isValid()){
            $em->persist($lieu);
            $em->flush();
            return $this->redirectToRoute('sortie_creation');
        }

        return $this->render("sortie/creationLieu.html.twig", [
            "lieuForm" => $lieuForme->createView()
        ]);
    }

    #[Route('/sortie/ajoutVille', name:"ville_creation")]
    public function ajoutVille(Request $request, EntityManagerInterface $em, VilleRepository $villeRepository)
    {
        $ville = new Ville();
        $villeForm  = $this->createForm(VilleType::class);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){
            $em->persist($ville);
            $em->flush();
            return $this->redirectToRoute('lieu_creation');
        }

        $villes = $villeRepository->findAll();

        return $this->render("sortie/creationVille.html.twig", [
            "villeForm" => $villeForm->createView(),
            "villes" => $villes
        ]);
    }


}
