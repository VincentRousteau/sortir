<?php

namespace App\Service;

use App\Entity\Sortie;
use Cassandra\Date;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeEtat
{

//    public function __construct(EntityManagerInterface $em)
//    {
//
//    }
//
//    public function change(Array $sorties): string
//    {
//
//
//
//        foreach ($sorties as $sortie){
//
//            $now = new \DateTime("now");
//            $nowAndDelay = $now->add(new DateInterval('PT' . $sortie->getDuree() . 'M'));
//
//
//            dd($nowAndDelay);
//
//        }
//
//    }





}