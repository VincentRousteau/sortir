<?php

namespace App\Controller;

use App\Form\EntiteFormulaire;
use App\Form\RechercheFormType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
    public function index(Request $request, SortieRepository $sortieRepository): Response
    {
        $personne = $this->getUser();

        $entiteFormulaire = new EntiteFormulaire();
        $form = $this->createForm(RechercheFormType::class, $entiteFormulaire);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $entiteFormulaire->setCampus($personne->getCampus());
        }

        $sorties = $sortieRepository->gigaRequeteDeSesMortsDeMerde($entiteFormulaire, $personne);

        return $this->render('main/home.html.twig', [
            'personne' => $personne,
            'formulaire' => $form->createView(),
            'toutesLesSorties' => $sorties,
        ]);
    }
}