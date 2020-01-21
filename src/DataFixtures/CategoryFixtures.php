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
        ],
        [
            'name' => 'Boîte à outils',
        ],
        [
            'name' => 'Cap 2022',
        ],
        [
            'name' => 'Memo pratique',
        ],
        [
            'name' => 'Annuaire',
        ],
        [
            'name' => 'Nos produits',
        ],
        [
            'name' => 'Checklist manager',
        ]

    ];

    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::CATEGORIES as $data) {
            $category = new Category();
            $category->setName($data['name']);
            $manager->persist($category);
            $this->addReference('categorie_' . $counter, $category);
            $counter++;
        }
        $manager->flush();
    }
}
