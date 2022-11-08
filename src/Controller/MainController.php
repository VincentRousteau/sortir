<?php

namespace App\Controller;

use App\Form\EntiteFormulaire;
use App\Form\RechercheFormType;
use App\Repository\SortieRepository;
use App\Service\ChangeEtat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
    public function index(Request $request, SortieRepository $sortieRepository, ChangeEtat $changeEtat): Response
    {

        $personne = $this->getUser();

        $entiteFormulaire = new EntiteFormulaire();
        $form = $this->createForm(RechercheFormType::class, $entiteFormulaire);

        $form->handleRequest($request);

        $sorties = $sortieRepository->gigaRequeteDeSesMortsDeMerde($entiteFormulaire, $personne);

        $changeEtat->change($sorties);



        return $this->render('main/home.html.twig', [
            'personne' => $personne,
            'formulaire' => $form->createView(),
            'toutesLesSorties' => $sorties,
        ]);
    }
}