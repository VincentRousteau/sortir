<?php

namespace App\Service;

use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;


class UpdateState
{

    public function __construct(private EtatRepository $stateRepository, private EntityManagerInterface $entityManager)
    {

    }
    public function update(Array $sorties): void
    {

        $now = new \DateTime("now");

        foreach ($sorties as $sortie){

            $start = $sortie->getDateHeureDebut();
            $end = clone $start;
            $end->modify($sortie->getDuree() . " minutes");
            $archived = clone $end;
            $archived->modify("+1 month");


            $isPassed = $now > $end;
            $isInProgress = $now > $start && $now < $end;
            $isArchived = $now > $archived;
            //$isClosed = $sortie->getDateLimiteInscription() < $now;


            if($isArchived){
                $sortie->setEtat($this->stateRepository->findOneByLibelle("historisé"));
            }
            elseif($isPassed){
                $sortie->setEtat($this->stateRepository->findOneByLibelle("passé"));
            }
            elseif($isInProgress){
                $sortie->setEtat($this->stateRepository->findOneByLibelle("en cours"));
            }

            $this->entityManager->persist($sortie);
            $this->entityManager->flush();
        }
    }




}