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

    const STEPS = [
        [
            'name' => 'Intégration au siège',
            'description' => '. Accueil par le responsable de pôle
				                . Visite des locaux',
            'duration' => 1,
            ],
        [
            'name' => 'Intégration au siège',
            'description' => '. Suivi du parcours de rencontre
				                . Bilan des deux jours d\'intégration au siège avec le manager',
            'duration' => 1,
        ],
        [
            'name' => 'Intégration sur la résidence pilote - Mise en situation',
            'description' => '. Mise en situation aux côtés du référent métier
				                . Doit comprendre un week-end',
            'duration' => 7,
        ],
        [
            'name' => 'Intégration du lieu de travail',
            'description' => '. Visite des locaux
				                . Présentation de l\'équipe',
            'duration' => 14,
        ],
        [
            'name' => 'Suivi de l\'intégration',
            'description' => '. Point avec les services supports
				                . Suivi hebdomadaire',
            'duration' => 14,
        ],
        [
            'name' => 'Bilan de la période écoulée',
            'description' => '. RDV bilan de la période écoulée',
            'duration' => 1,
        ],
        [
            'name' => 'Suivi mensuel',
            'description' => '. Entretien de suivi avec le responsable de pôle
				                . Si prolongation de la période d\'essai, 2nd entretien',
            'duration' => 60,
        ],
        [
            'name' => 'Bilan de la fin d\'intégration',
            'description' => '. Entretien de suivi mensuel
				                . Elaboration du plan de développement personnalisé',
            'duration' => 180,
        ],

        ];
}
