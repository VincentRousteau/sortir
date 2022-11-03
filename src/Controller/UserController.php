<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfileType;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    public function newProfile(AuthenticationUtils $authenticationUtils, ParticipantRepository $participantRepository, Request $request): Response
    {
        $email = $authenticationUtils->getLastUsername();
        $personne = $participantRepository->findOneByEmail($email);
        $profile = new Participant();
        $form = $this->createForm(ProfileType::class, $personne, [
            'action' => $this->generateUrl('app_user_profile'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($profile);
            $em->flush();

            $this->addFlash('success', 'Informations correctement enregistrées !');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/account.html.twig', [
            'userForm' => $form->createView(),
            'user'=> $personne
        ]);
    }
}
