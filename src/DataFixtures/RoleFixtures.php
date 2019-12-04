<?php


namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{

    const ROLES = [
        'collab' => [
            'name' => 'Collaborateur',
            'identifier' => 'collab'
        ],
        'manager' => [
            'name' => 'Manager',
            'identifier' => 'manager'
        ],
        'admin' => [
            'name' => 'Administrateur',
            'identifier' => 'admin'
        ],
        ];

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::ROLES as $data) {
            $role = new Role();
            $role->setName($data['name']);
            $role->setIdentifier($data['identifier']);
            $manager->persist($role);
            $this->addReference('role_' . $counter, $role);
            $counter++;
        }
        $manager->flush();
    }
}
