<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker;
use App\DataFixtures\VilleFixtures;

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

        $lieu = new Lieu();
        $lieu->setNom("Aqualand");
        $lieu->setRue("10 rue de Rennes");
        $lieu->setLatitude(12345);
        $lieu->setLongitude(12345);
        $lieu->setVille($this->getReference("rennes"));

        $manager->persist($lieu);
        $this->addReference("aqualand", $lieu);

        $lieu = new Lieu();
        $lieu->setNom("Gaumont");
        $lieu->setRue("10 rue de Paris");
        $lieu->setLatitude(12345);
        $lieu->setLongitude(12345);
        $lieu->setVille($this->getReference("rennes"));

        $manager->persist($lieu);
        $this->addReference("gaumont", $lieu);

        $faker = Faker\Factory::create('fr_FR');
        $faker = Factory::create('fr_FR');
        $lieu = array();
        for ($i = 1; $i < 30; $i++) {
            $list = array("Piscine", 'Cinéma', 'Patinoire', 'Bowling', 'Mini-golf', 'Karaoké', 'Biture', 'Escalade', 'Switch & Chill');
            $lieu[$i] = new Lieu();
            $lieu[$i]->setNom(array_rand(array_flip($list), 1));
            $lieu[$i]->setRue($faker->streetAddress);
            $lieu[$i]->setLatitude($faker->latitude);
            $lieu[$i]->setLongitude($faker->longitude);
            $lieu[$i]->setVille($this->getReference("ville".$faker->numberBetween(1,24)));
            $manager->persist($lieu[$i]);
            $this->addReference("lieu$i",$lieu[$i]);

            $manager->flush();
        }
    }


        public
        function getDependencies()
        {
            return [VilleFixtures::class];
        }
    }
