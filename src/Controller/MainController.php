<?php

namespace App\Controller;

use App\Form\EntiteFormulaire;
use App\Form\RechercheFormType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
    public function index(Request $request, AuthenticationUtils $authenticationUtils, ParticipantRepository $participantRepository, SortieRepository $sortieRepository): Response
    {
        $email = $authenticationUtils->getLastUsername();
        $personne = $participantRepository->findOneByEmail($email);

        $entiteFormulaire = new EntiteFormulaire();
        $form = $this->createForm(RechercheFormType::class, $entiteFormulaire);

        $form->handleRequest($request);

        if(!$form->isSubmitted()){
            $entiteFormulaire->setCampus($personne->getCampus());
        }

        $sorties = $sortieRepository->gigaRequeteDeSesMortsDeMerde($entiteFormulaire, $personne);

        dump($entiteFormulaire);

        return $this->render('main/home.html.twig', [
            'personne' => $personne,
            'formulaire' => $form->createView(),
            'toutesLesSorties' => $sorties,
        ]);
    }
}