<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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
        $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRole($this->getReference('role_2'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setResidence($this->getReference('residence_' . rand(0, 4)));
        $manager->persist($user);

        // Creates 2 managers
        for ($i = 0; $i < 2; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRole($this->getReference('role_1'));
            $user->setRoles(['ROLE_MANAGER']);
            $user->setPosition($this->getReference('position_0'));
            $user->setResidence($this->getReference('residence_' . rand(0, 4)));
            $manager->persist($user);
            $this->addReference('manager_' . $i, $user);
        }

        // Creates 7 collaborators associated to a manager
        for ($i = 0; $i < 7; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRole($this->getReference('role_0'));
            $user->setRoles(['ROLE_COLLABORATOR']);
            $user->setManager($this->getReference('manager_' . rand(0, 1)));
            $user->setPosition($this->getReference('position_' . rand(1, 2)));
            $user->setResidence($this->getReference('residence_' . rand(0, 4)));
            $user->setMentor($faker->name);
            $user->setReferent($faker->name);
            $user->setStartDate($faker->dateTimeThisYear);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
