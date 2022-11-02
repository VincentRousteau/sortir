<?php

namespace App\DataFixtures;

use App\DataFixtures\CampusFixtures;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


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
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ParticipantFixtures::class, CampusFixtures::class, LieuFixtures::class, EtatFixtures::class];
    }
}
