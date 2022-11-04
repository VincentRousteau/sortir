<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\EditProfileType;
use App\Repository\ParticipantRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    #[Route('/user/account', name: 'show_profile')]

    public function profile(Participant $user): Response
    {
        if ($user->isActif()){
            throw $this->createNotFoundException("Utilisateur désactivé");

        }
            return $this->render('user/account.html.twig',[
                'user' => $user
            ]);
    }

    #[Route('/user/edit', name: 'edit_profile')]

    public function new(AuthenticationUtils $authenticationUtils, ParticipantRepository $participantRepository, Request $request, FileUploader $fileUploader, EntityManagerInterface $em): Response
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        //test des credentials sur la base de données.
        $email = $authenticationUtils->getLastUsername();
        $personne = $participantRepository->findOneByEmail($email);
        $profile = new Participant();
        $profile->setDateCreated(new \DateTime());
        $profileForm = $this->createForm(EditProfileType::class, $personne, [
            'action' => $this->generateUrl('user_edit'),
            'method' => 'GET',
        ]);
        //return $this->redirectToRoute('user_profile', ["id" => $user->getId()]);


        //Récupération du contenu des champs à charger dans l'objet $profile.
        $profileForm->handleRequest($request);

        //Vérifier si l'utilisateur est en train d'envoyer le formulaire
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $file = $personne->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('avatar_directory'), $fileName);
            $personne->setPhoto($fileName);
            return new Response("Chargement de la photo bien effectuée");


            //Enregistrer le nouveau profil en base de données
            $em->persist($profile);
            $em->flush();

            $this->addFlash('success', 'Informations correctement enregistrées !');
            return $this->redirectToRoute('user_account');
        }

        return $this->render('user/account.html.twig', [
            'userForm' => $profileForm->createView(),
            'user'=> $personne
        ]);
    }
}
