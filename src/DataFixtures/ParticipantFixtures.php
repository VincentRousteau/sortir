<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParticipantFixtures extends Fixture
{

    public const ADMIN_REFERENCE = 'admin-user';

    public function __construct(private UserPasswordHasherInterface $hasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $admin = new Participant();
        $admin->setEmail("admin@admin.com");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $admin->setNom("LM");
        $admin->setPrenom("Ludo");
        $admin->setTelephone("0666666666");
        $admin->setActif(true);
        $admin->setCampus($this->getReference("chartres"));
        $manager->persist($admin);

        $user = new Participant();
        $user->setEmail("user@user.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->hasher->hashPassword($user, 'user'));
        $user->setNom("Rousteau");
        $user->setPrenom("Vincent");
        $user->setTelephone("0777777777");
        $user->setActif(true);
        $user->setCampus($this->getReference("nantes"));
        $manager->persist($user);

        $user2 = new Participant();
        $user2->setEmail("user2@user2.com");
        $user2->setRoles(["ROLE_USER"]);
        $user2->setPassword($this->hasher->hashPassword($user2, 'user2'));
        $user2->setNom("Moreau");
        $user2->setPrenom("Samuel");
        $user2->setTelephone("0777777777");
        $user2->setActif(true);
        $user2->setCampus($this->getReference("niort"));
        $manager->persist($user2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CampusFixtures::class];
    }
}
