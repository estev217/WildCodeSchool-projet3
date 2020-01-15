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
            'identifier' => 'group'
        ],
        [
            'name' => 'Nos métiers ',
            'identifier' => 'occupation'
        ],
        [
            'name' => 'Cap 2022',
            'identifier' => 'futur'
        ],
        [
            'name' => 'Memo pratique',
            'identifier' => 'memo'
        ],
        [
            'name' => 'Annuaire',
            'identifier' => 'contact'
        ],
        [
            'name' => 'Nos produits',
            'identifier' => 'offer'
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