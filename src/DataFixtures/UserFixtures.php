<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
     private $passwordHasher;

     public function __construct(UserPasswordHasherInterface $passwordHasher)
     {
         $this->passwordHasher = $passwordHasher;
     }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setEmail('test@admin.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin,'test'));

        $userCustomer = new User();
        $userCustomer->setEmail('test@customer.com');
        $userCustomer->setRoles(['ROLE_USER']);
        $userCustomer->setPassword($this->passwordHasher->hashPassword($userCustomer,'test'));

        $manager->persist($userAdmin);
        $manager->persist($userCustomer);
        $manager->flush();
    }
}
