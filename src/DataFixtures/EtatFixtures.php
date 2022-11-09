<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $etat = new Etat();
        $etat->setLibelle("Ouvert");
        $manager->persist($etat);
        $this->addReference("ouvert", $etat);

        $etat1 = new Etat();
        $etat1->setLibelle("En creation");
        $manager->persist($etat1);
        $this->addReference("cree", $etat1);

        $etat2 = new Etat();
        $etat2->setLibelle("Fermé");
        $manager->persist($etat2);
        $this->addReference("ferme", $etat2);

        $etat3 = new Etat();
        $etat3->setLibelle("En cours");
        $manager->persist($etat3);
        $this->addReference("encours", $etat3);

        $etat4 = new Etat();
        $etat4->setLibelle("Passé");
        $manager->persist($etat4);
        $this->addReference("passe", $etat4);

        $etat5 = new Etat();
        $etat5->setLibelle("Historisé");
        $manager->persist($etat5);
        $this->addReference("historise", $etat5);


        $manager->flush();
    }
}
