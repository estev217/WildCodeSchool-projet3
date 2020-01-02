<?php

namespace App\DataFixtures;

use App\Entity\Residence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ResidenceFixtures extends Fixture
{

    const RESIDENCES = [
        [
            'name' => 'Les Rives de St Brice - Village de pêcheur',
            'city' =>'Arès',
        ],
        [
            'name' => 'Les Sables Vignier',
            'city' => 'Saint-Georges d\'Oléron',
        ],
        [
            'name' => 'Kermael',
            'city' => 'Saint Briac-sur-Mer',
        ],
        [
            'name' => 'Le Grand Lodge',
            'city' => 'Châtel',
        ],
        [
            'name' => 'La Soulane',
            'city' => 'Loudenvielle',
        ],


    ];

    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::RESIDENCES as $data) {
            $residence = new Residence();
            $residence->setName($data['name']);
            $residence->setCity($data['city']);
            $manager->persist($residence);
            $this->addReference('residence_' . $counter, $residence);
            $counter++;
        }
        $manager->flush();
    }
}
