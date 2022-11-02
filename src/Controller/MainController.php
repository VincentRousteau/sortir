<?php

namespace App\Controller;

use App\Form\RechercheFormType;
use App\Repository\ParticipantRepository;
use App\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(AuthenticationUtils $authenticationUtils, ParticipantRepository $participantRepository): Response
    {
        $email = $authenticationUtils->getLastUsername();
        $personne = new Participant();
        $personne = $participantRepository->findOneByEmail($email);

        $sortiesOrganisees = $participantRepository->findAllSortiesOrganisees($personne);

        $sortiesForm = $this->createForm(RechercheFormType::class);

        return $this->render('main/home.html.twig', [
            'personne' => $personne,
            'sortiesForm' => $sortiesForm->createView(),
            'sortiesOrganisees'=>$sortiesOrganisees
        ]);
    }
}
