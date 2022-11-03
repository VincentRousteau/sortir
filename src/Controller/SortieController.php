<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
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
        $serieForm  = $this->createForm(SortieType::class, $sortie);

        $serieForm->handleRequest($request);

        if($serieForm->isSubmitted() && $serieForm->isValid()){
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render("sortie/creationSortie.html.twig", [
            "sortieForm" => $serieForm->createView()
        ]);
    }
}
