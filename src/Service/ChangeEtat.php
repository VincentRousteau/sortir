<?php

namespace App\Service;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Cassandra\Date;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeEtat
{

    public function __construct(EntityManagerInterface $em)
    {

    }

    public function change(SortieRepository $sortieRepository, EntityManagerInterface $em, Array $sorties): void
    {



        foreach ($sorties as $sortie){

            $now = new \DateTime("now");
            $start = $sortie->getDateHeureDebut();

            $end = clone $start;
            $end->modify($sortie->getDuree() . " minutes");

            $archive = clone $end;
            $archive->modify("1 month");

            $isPassed = $now > $end;
            $isInProgress = $now > $start && $now < $end;
            $isArchived = $now > $archive;
            $isClosed = count($sortie->getParticipants()) >= $sortie->getNbInscriptionsMax();


            if($isPassed){
                $sortie.setEtat($this->getReference("Passé"));
            }

            $em->persist($sortie);
            $em->flush();
        }

    }





}