<?php

namespace App\DataFixtures;


use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{

    public const ADMIN_REFERENCE = 'admin-user';

    public function __construct(private UserPasswordHasherInterface $hasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        //$campus = $manager->getRepository(Campus::class)->findAll();
        //$campus[random_int(0, strlen($campus))];

        $admin = new Participant();
        $admin->setEmail("admin@admin.com");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $admin->setNom("LM");
        $admin->setPrenom("Ludo");
        $admin->setTelephone("0666666666");
        $admin->setActif(true);
        $admin->setPseudo("Ludo");
        $admin->setCampus($this->getReference("chartres"));
        $manager->persist($admin);
        $this->addReference("ludo", $admin);

        $user = new Participant();
        $user->setEmail("user@user.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->hasher->hashPassword($user, 'user'));
        $user->setNom("Rousteau");
        $user->setPrenom("Vincent");
        $user->setTelephone("0777777777");
        $user->setActif(true);
        $user->setPseudo("Vincente");
        $user->setCampus($this->getReference("nantes"));
        $manager->persist($user);
        $this->addReference("vincente", $user);

        $user2 = new Participant();
        $user2->setEmail("user2@user2.com");
        $user2->setRoles(["ROLE_USER"]);
        $user2->setPassword($this->hasher->hashPassword($user2, 'user2'));
        $user2->setNom("Moreau");
        $user2->setPrenom("Samuel");
        $user2->setTelephone("0777777777");
        $user2->setActif(true);
        $user2->setPseudo("Sam");
        $user2->setCampus($this->getReference("niort"));
        $manager->persist($user2);
        $this->addReference("sam", $user2);


        $faker = Faker\Factory::create('fr_FR');
        $faker = Factory::create('fr_FR');
        $participant = Array();
        for ($i = 1; $i < 50; $i++) {
            $participant[$i] = new Participant();
            $participant[$i]->setNom($faker->lastName);
            $participant[$i]->setPrenom($faker->firstName);
            $participant[$i]->setPassword($this->hasher->hashPassword($participant[$i], "user"));
            $participant[$i]->setEmail($faker->unique()->email);
            $participant[$i]->setRoles(["ROLE_USER"]);
            $participant[$i]->setPseudo($faker->unique()->lastName);
            $participant[$i]->setTelephone($faker->numberBetween(100000000,999999999) );
            $participant[$i]->setActif(true);
            $participant[$i]->setCampus($this->getReference("campus".$faker->numberBetween(1,9)));
            $this->addReference("participant$i", $participant[$i]);

            $manager->persist($participant[$i]);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CampusFixtures::class];
    }
}
