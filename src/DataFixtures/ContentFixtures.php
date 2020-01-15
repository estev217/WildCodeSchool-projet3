<?php


namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ContentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');
        for ($i=0; $i<=5; $i++) {
            $content = new Content();
            $content->setTitle($faker->sentence);
            $content->setUser($this->getReference('admin'));
            $content->setContent($faker->text);
            $content->setCategory($this->getReference('categorie_' . $i));
            $manager->persist($content);
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
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
