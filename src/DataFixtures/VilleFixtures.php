<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Factory;
use Faker\Provider\Address;

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

        $ville2 = new Ville();
        $ville2->setNom("Nantes");
        $ville2->setCodePostal("44000");
        $manager->persist($ville2);
        $this->addReference("nantesVille", $ville2);

        $ville3 = new Ville();
        $ville3->setNom("Cesson-Sévigné");
        $ville3->setCodePostal("35510");
        $manager->persist($ville3);
        $this->addReference("cesson", $ville3);

        $faker = Faker\Factory::create('fr_FR');
        $faker = Factory::create('fr_FR');
        $ville = Array();
        for ($i = 1; $i < 25; $i++) {
            $ville[$i] = new Ville();
            $ville[$i]->setNom($faker->city);
            $ville[$i]->setCodePostal("$i$i");
            $manager->persist($ville[$i]);
            $this->addReference("ville$i",$ville[$i]);
        }

        $manager->flush();
    }
}
