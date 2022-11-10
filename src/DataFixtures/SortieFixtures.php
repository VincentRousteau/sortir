<?php

namespace App\DataFixtures;

use App\DataFixtures\CampusFixtures;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        //$users = $manager->getRepository(Participant::class)->findAll();

        $sortie = new Sortie();
        $sortie->setNom('Patinoire');
        $sortie->setDateHeureDebut(new \DateTime("2022-12-09 18:30:00"));
        $sortie->setDateLimiteInscription(new \DateTime("2022-12-02 18:30:00"));
        $sortie->setDuree(90);
        $sortie->setNbInscriptionsMax(15);
        $sortie->setInfosSortie("Sortie à la patinoire le Blizz avec apéro avant et après");
        $sortie->setLieu($this->getReference("blizz"));
        $sortie->setCampus($this->getReference("chartres"));
        $sortie->setEtat($this->getReference("ouvert"));
        $sortie->setOrganisateur($this->getReference("sam"));
        $sortie->addParticipant($this->getReference("sam"));

        $manager->persist($sortie);

        $sortie2 = new Sortie();
        $sortie2->setNom('Piscine');
        $sortie2->setDateHeureDebut(new \DateTime("2022-11-10 18:30:00"));
        $sortie2->setDateLimiteInscription(new \DateTime("2022-11-02 18:30:00"));
        $sortie2->setDuree(120);
        $sortie2->setNbInscriptionsMax(15);
        $sortie2->setInfosSortie("Sortie à la piscine");
        $sortie2->setLieu($this->getReference("aqualand"));
        $sortie2->setCampus($this->getReference("niort"));
        $sortie2->setEtat($this->getReference("encours"));
        $sortie2->setOrganisateur($this->getReference("vincente"));
        $sortie2->addParticipant($this->getReference("vincente"));

        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->setNom('Cinema');
        $sortie3->setDateHeureDebut(new \DateTime("2023-01-10 18:30:00"));
        $sortie3->setDateLimiteInscription(new \DateTime("2023-01-02 18:30:00"));
        $sortie3->setDuree(120);
        $sortie3->setNbInscriptionsMax(15);
        $sortie3->setInfosSortie("Sortie à la piscine");
        $sortie3->setLieu($this->getReference("gaumont"));
        $sortie3->setCampus($this->getReference("niort"));
        $sortie3->setEtat($this->getReference("cree"));
        $sortie3->setOrganisateur($this->getReference("ludo"));
        $sortie3->addParticipant($this->getReference("ludo"));

        $manager->persist($sortie3);

        $sortie4 = new Sortie();
        $sortie4->setNom('Cinema');
        $sortie4->setDateHeureDebut(new \DateTime("2022-08-10 18:30:00"));
        $sortie4->setDateLimiteInscription(new \DateTime("2022-08-02 18:30:00"));
        $sortie4->setDuree(120);
        $sortie4->setNbInscriptionsMax(15);
        $sortie4->setInfosSortie("Sortie à la piscine");
        $sortie4->setLieu($this->getReference("gaumont"));
        $sortie4->setCampus($this->getReference("nantes"));
        $sortie4->setEtat($this->getReference("passe"));
        $sortie4->setOrganisateur($this->getReference("ludo"));
        $sortie4->addParticipant($this->getReference("ludo"));

        $manager->persist($sortie4);

        //$faker = Faker\Factory::create('fr_FR');
        $faker = Factory::create('fr_FR');
        $sortie1 = Array();
        for ($i = 1; $i < 100; $i++) {
            $list = ["Piscine", 'Cinéma', 'Patinoire', 'Bowling', 'Mini-golf', 'Karaoké', 'Biture', 'Escalade', 'Switch & Chill'];
            $sortie1[$i] = new Sortie();
            $sortie1[$i]->setNom(array_rand(array_flip($list), 1));
            $sortie1[$i]->setDateHeureDebut($faker->dateTimeBetween('-1 week', '+1 month'));
            $sortie1[$i]->setDateLimiteInscription($faker->dateTimeBetween('-3 week', '-1 week'));
            $sortie1[$i]->setDuree($faker->numberBetween(30,300));
            $sortie1[$i]->setNbInscriptionsMax($faker->numberBetween(3,50));
            $sortie1[$i]->setInfosSortie($faker->email);
            $sortie1[$i]->setLieu($this->getReference("lieu".$faker->numberBetween(1,29)));
            $sortie1[$i]->setCampus($this->getReference("campus".$faker->numberBetween(0,8)));
            $sortie1[$i]->setEtat($this->getReference("ouvert"));
            $sortie1[$i]->setOrganisateur($this->getReference("participant".$faker->numberBetween(1,49)));
            $sortie1[$i]->addParticipant($this->getReference("participant".$faker->numberBetween(1,49)));

            for($j=0;$j<=$faker->numberBetween(0,$sortie1[$i]->getNbInscriptionsMax());$j++){
                $sortie1[$i]->addParticipant($this->getReference("participant".$faker->numberBetween(1,49)));
            }

            $manager->persist($sortie1[$i]);

            $this->addReference("sortie$i",$sortie1[$i]);
        }


        $manager->flush();
    }


    public function getDependencies()
    {
        return [ParticipantFixtures::class, CampusFixtures::class, LieuFixtures::class, EtatFixtures::class];
    }
}
