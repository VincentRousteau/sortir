<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $ville = new Ville();
        $ville->setNom("Rennes");
        $ville->setCodePostal("35000");
        $manager->persist($ville);
        $this->addReference("rennes", $ville);

        $manager->flush();
    }
}
