<?php

namespace App\DataFixtures;

use App\Entity\Campus;
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

        $campus = Array();
        for ($i = 0; $i < 9; $i++) {
            $list = array('Paris', 'Toulouse', 'Rennes', 'Quimper', 'Montpellier', 'Marseille', 'Strasbourg', 'Lille', 'Brest');
            $campus[$i] = new Campus();
            $campus[$i]->setNom($list[$i]);
            $manager->persist($campus[$i]);
            $this->addReference("campus$i",$campus[$i]);
        }

        $manager->flush();
    }

}
