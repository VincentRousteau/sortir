<?php

namespace App\Controller;

use App\Form\RechercheFormType;
use App\Repository\ParticipantRepository;
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
        $personne = $participantRepository->findOneByEmail($email);

        $sortiesForm = $this->createForm(RechercheFormType::class);

        return $this->render('main/home.html.twig', [
            'personne'=> $personne,
            'sortiesForm' => $sortiesForm->createView()
        ]);
    }

    public function getInfosByEmail(string $email)
    {
        $qb = $this->createQueryBuilder();

        $qb->select('prenom', 'nom')
            ->from('participants', 'p')
            ->where('p.email = ?1')
            ->setParameter(1, $email);

        return $qb->getQuery()->getResult();
    }

    public function filndAllSortiesOrganisees()
    {

        $qb = $this->createQueryBuilder('sorties');

        $qb->addSelect('sorties')
            ->orderBy('');

    }
}
