<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $lieu = new Lieu();
        $lieu->setNom("Patinoire Le Blizz");
        $lieu->setRue("12 rue des Gayeulles");
        $lieu->setLatitude(12345);
        $lieu->setLongitude(12345);
        $lieu->setVille($this->getReference("rennes"));

        $manager->persist($lieu);
        $this->addReference("blizz", $lieu);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [VilleFixtures::class];
    }
}
