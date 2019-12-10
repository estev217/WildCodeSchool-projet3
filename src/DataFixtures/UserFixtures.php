<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        // Creates 1 admin
        $user = new User();
        $user->setFirstname($faker->firstName);
        $user->setLastname($faker->lastName);
        $user->setEmail($faker->email);
        $user->setPassword($faker->password);
        $user->setCreatedAt($faker->dateTime);
        $user->setRole($this->getReference('role_2'));
        $manager->persist($user);

        // Creates 2 managers
        for ($i = 0; $i < 2; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setCreatedAt($faker->dateTime);
            $user->setRole($this->getReference('role_1'));
            $manager->persist($user);
            $this->addReference('manager_' . $i, $user);
        }

        // Creates 7 collaborators associated to a manager
        for ($i = 0; $i < 7; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setCreatedAt($faker->dateTime);
            $user->setRole($this->getReference('role_0'));
            $number = rand(0, 1);
            $user->setManager($this->getReference('manager_' . $number));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
