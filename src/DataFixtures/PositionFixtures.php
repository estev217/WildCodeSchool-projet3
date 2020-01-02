<?php

namespace App\DataFixtures;

use App\Entity\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PositionFixtures extends Fixture
{

    const POSITIONS = [
        'Responsable de site',
        'Animateur',
        'Chef de cuisine',
    ];

    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::POSITIONS as $data) {
            $position = new Position();
            $position->setName($data);
            $manager->persist($position);
            $this->addReference('position_' . $counter, $position);
            $counter++;
        }

        $manager->flush();
    }
}
