<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/{id}', name: 'sortie_detail', requirements: ['id' => '\d+'])]
    public function index(SortieRepository $sortieRepository, int $id): Response
    {

        $sortie = $sortieRepository->find($id);

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }
}
