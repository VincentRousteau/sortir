<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ListSortieController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $prenom = $authenticationUtils->getLastUsername();
        return $this->render('main/home.html.twig', [
                "prenom" => $prenom
        ]);
    }
}
