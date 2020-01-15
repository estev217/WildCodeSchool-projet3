<?php


namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        [
            'name' => 'Le groupe',
            'identifier' => 'Présentation de l\entreprise'
        ],
        [
            'name' => 'Nos métiers ',
            'identifier' => 'L\efficacité du réseau'
        ],
        [
            'name' => 'Cap 2022',
            'identifier' => 'Notre politique de croissance'
        ],
        [
            'name' => 'Memo pratique',
            'identifier' => 'Guide du nouveau collaborateur'
        ],
        [
            'name' => 'Annuaire',
            'identifier' => 'Qui contacter en cas de besoin'
        ],
        [
            'name' => 'Nos produits',
            'identifier' => 'Les différents types de résidences'
        ],

    ];

    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::CATEGORIES as $data) {
            $category = new Category();
            $category->setName($data['name']);
            $category->setIdentifier($data['identifier']);
            $manager->persist($category);
            $this->addReference('categorie_' . $counter, $category);
            $counter++;
        }
        $manager->flush();
    }
}