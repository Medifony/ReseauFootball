<?php

namespace App\DataFixtures;

use DateTime;


use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $date = new DateTime('first day of last month');

        #Enregistrement du role Admin}
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setPrenom('Mehdi')
                  ->setNom('Testeur')
                  ->setPseudo('Mehdii')
                  ->setEmail('mehdi@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setSlug($adminUser->getPseudo())
                  ->setDateCrea($date)
                  ->setPresentation($faker->paragraph(2))
                  ->addUserRole($adminRole);
                  
        $manager->persist($adminUser);

        for($i = 1; $i <=20; $i++) {
            $user = new User();

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setPrenom($faker->firstName)
                 ->setNom($faker->lastName)
                 ->setPseudo($faker->word)
                 ->setEmail($faker->email)
                 ->setHash($hash) 
                 ->setSlug($user->getPseudo())
                 ->setDateCrea($date)
                 ->setPresentation($faker->paragraph(2));

            $manager->persist($user);
        }
        $manager->flush();
    }
}
