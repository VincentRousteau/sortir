<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\EditProfileType;
use App\Form\ProfileType;
use App\Repository\ParticipantRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ProfileController extends AbstractController
{

    #[Route('/profile/{id}', name: 'profile_detail', requirements: ['id' => '\d+'])]
    public function details(ParticipantRepository $participantRepository, int $id): Response
    {
        $user = $this->getUser();
        $profile = $participantRepository->find($id);

        if ($user->getId() != $profile->getId()) {
            return $this->render('profile/detail.html.twig', [
                'profile' => $profile
            ]);
        } else {
            return $this->redirectToRoute('profile_edit');
        }

    }

    #[Route('/profile/edit', name: 'profile_edit')]
    public function edit(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher, FileUploader $fileUploader): Response
    {
        $user = $this->getUser();

        $profileForm = $this->createForm(EditProfileType::class, $user);

        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {

            /** @var UploadedFile $image */
            $image = $profileForm->get('image')->getData();

            if ($image) {
                $avatar = $fileUploader->upload($image, '/avatars');
                $user->setImage($avatar);
            }

            // Encode le plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $profileForm->get('password')->getData()
                )
            );

            $em->persist($user);
            $em->flush();


            $this->addFlash('success', 'Le profil a bien été enregistré');

            return $this->redirectToRoute('homepage');

        }

        return $this->render('profile/edit.html.twig', [
            'profile' => $user,
            'profileForm' => $profileForm->createView()
        ]);

    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/profile/new', name: 'profile_new')]
    public function new(AuthenticationUtils $authenticationUtils, ParticipantRepository $participantRepository, Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $profile = new Participant();
        $email = $authenticationUtils->getLastUsername();
        $personne = $participantRepository->findOneByEmail($email);
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $profileForm = $this->createForm(ProfileType::class, $personne, [
            'action' => $this->generateUrl('profile'),
            'method' => 'GET',
        ]);


        //Récupération des données pour les insérer dans l'objet $profile. Handlerequest est très important
        $profileForm->handleRequest($request);


        //Vérifier si l'utilisateur est en train d'envoyer le formulaire
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {


            //Uploader les images
            /** @var UploadedFile $avatar */
            $avatar = $profileForm->get('Image')->getData();
            if ($avatar) {
                $avatar = $fileUploader->upload($avatar, '/images/avatars');
                $profile->setImage($avatar);
            }

            // Enregister l'avatar en BDD
            $em->persist($profile);
            $em->flush();

            $this->addFlash('success', 'Le profile a bien été enregistré');
            // Rediriger l'internaute vers la liste des profils
            return $this->redirectToRoute('profiles_list');


        }

        return $this->render('profile/new.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
    }

    //Route /series/1       series_details details.html.twig                   Arriver dans une page qui présente les détails d'une série

    //Route /series/new/new series_new new.html.twig
}


