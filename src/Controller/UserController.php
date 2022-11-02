<?php

namespace App\Controller;

use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    public function newProfil(): Response
    {
        $profil = new ();
        $form = $this->createForm(ProfileType::class, $profil);
        $form->handleRequest($)


        return $this->render('count.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
