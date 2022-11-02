<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $etat = new Etat();
        $etat->setLibelle("Ouvert");
        $manager->persist($etat);
        $this->addReference("ouvert", $etat);

        $manager->flush();
    }
}
