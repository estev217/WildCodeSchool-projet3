<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
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

        // Creates Laureen admin
        $user = new User();
        $user->setFirstname('Laureen');
        $user->setLastname('Dumas');
        $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRole($this->getReference('role_2'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setResidence($this->getReference('residence_' . rand(0, 4)));
        $user->setTelephone($faker->phoneNumber);
        $user->setStartDate($faker->dateTimeThisDecade);
        $manager->persist($user);
        $this->addReference('admin', $user);

        // Creates 1 random admin
        $user = new User();
        $user->setFirstname($faker->firstName);
        $user->setLastname($faker->lastName);
        $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRole($this->getReference('role_2'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setResidence($this->getReference('residence_' . rand(0, 4)));
        $user->setTelephone($faker->phoneNumber);
        $user->setStartDate($faker->dateTimeThisDecade);
        $manager->persist($user);

        // Creates Estevao manager
        $user = new User();
        $user->setFirstname('Estevao');
        $user->setLastname('Fernandes');
        $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRole($this->getReference('role_1'));
        $user->setRoles(['ROLE_MANAGER']);
        $user->setPosition($this->getReference('position_0'));
        $user->setResidence($this->getReference('residence_' . rand(0, 4)));
        $user->setTelephone($faker->phoneNumber);
        $user->setStartDate($faker->dateTimeThisDecade);
        $manager->persist($user);
        $this->addReference('manager_4', $user);


        // Creates 4 managers
        for ($i = 0; $i < 4; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRole($this->getReference('role_1'));
            $user->setRoles(['ROLE_MANAGER']);
            $user->setPosition($this->getReference('position_0'));
            $user->setResidence($this->getReference('residence_' . rand(0, 4)));
            $user->setTelephone($faker->phoneNumber);
            $user->setStartDate($faker->dateTimeThisDecade);
            $manager->persist($user);
            $this->addReference('manager_' . $i, $user);
        }

        // Creates Philippe Collaborator
        $user = new User();
        $user->setFirstname('Philippe');
        $user->setLastname('Urban');
        $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRole($this->getReference('role_0'));
        $user->setRoles(['ROLE_COLLABORATOR']);
        $user->setManager($this->getReference('manager_' . rand(0, 4)));
        $user->setPosition($this->getReference('position_' . rand(1, 2)));
        $user->setResidence($this->getReference('residence_' . rand(0, 4)));
        $user->setMentor($this->getReference('manager_' . rand(0, 4)));
        $user->setReferent($this->getReference('manager_' . rand(0, 4)));
        $user->setTelephone($faker->phoneNumber);
        $user->setStartDate($faker->dateTimeThisYear);
        $manager->persist($user);

        // Creates Anne Collaborator
        $user = new User();
        $user->setFirstname('Anne');
        $user->setLastname('Quiedeville');
        $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRole($this->getReference('role_0'));
        $user->setRoles(['ROLE_COLLABORATOR']);
        $user->setManager($this->getReference('manager_' . rand(0, 4)));
        $user->setPosition($this->getReference('position_' . rand(1, 2)));
        $user->setResidence($this->getReference('residence_' . rand(0, 4)));
        $user->setMentor($this->getReference('manager_' . rand(0, 4)));
        $user->setReferent($this->getReference('manager_' . rand(0, 4)));
        $user->setTelephone($faker->phoneNumber);
        $user->setStartDate($faker->dateTimeThisYear);
        $manager->persist($user);

        // Creates 7 collaborators associated to a manager
        for ($i = 0; $i < 7; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@nemea.fr'));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRole($this->getReference('role_0'));
            $user->setRoles(['ROLE_COLLABORATOR']);
            $user->setManager($this->getReference('manager_' . rand(0, 4)));
            $user->setPosition($this->getReference('position_' . rand(1, 2)));
            $user->setResidence($this->getReference('residence_' . rand(0, 4)));
            $user->setMentor($this->getReference('manager_' . rand(0, 4)));
            $user->setReferent($this->getReference('manager_' . rand(0, 4)));
            $user->setTelephone($faker->phoneNumber);
            $user->setStartDate($faker->dateTimeThisYear);
            $manager->persist($user);
            $this->addReference('collaborator_' . $i, $user);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [RoleFixtures::class, PositionFixtures::class, ResidenceFixtures::class];
    }
}
