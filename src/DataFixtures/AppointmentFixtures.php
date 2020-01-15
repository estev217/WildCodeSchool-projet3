<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 7; $i++) {
            $appointment = new Appointment();
            $appointment->setDate($faker->dateTimeThisMonth);
            $appointment->setUser($this->getReference('collaborator_' . rand(0, 6)));
            $appointment->setPartner($this->getReference('manager_' . rand(0, 1)));
            $manager->persist($appointment);
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
        return [UserFixtures::class];
    }
}
