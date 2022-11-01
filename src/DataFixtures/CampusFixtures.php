<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $campus1 = new Campus();
        $campus1->setNom("Chartres de Bretagne");
        $manager->persist($campus1);
        $this->addReference("chartres", $campus1);

        $campus2 = new Campus();
        $campus2->setNom("Nantes");
        $manager->persist($campus2);
        $this->addReference("nantes", $campus2);

        $campus3 = new Campus();
        $campus3->setNom("Niort");
        $manager->persist($campus3);
        $this->addReference("niort", $campus3);

        $manager->flush();
    }

}
