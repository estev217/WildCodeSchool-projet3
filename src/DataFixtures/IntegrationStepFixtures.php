<?php


namespace App\DataFixtures;

use App\Entity\IntegrationStep;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IntegrationStepFixtures extends Fixture
{

    const STEPS = [
        [
            'name' => 'Intégration au siège',
            'description' => 'Accueil par le responsable de pôle \n 
            Visite des locaux',
            'duration' => 1,
        ],
        [
            'name' => 'Intégration au siège',
            'description' => 'Suivi du parcours de rencontre \n 
            Bilan des deux jours d\'intégration au siège avec le manager',
            'duration' => 1,
        ],
        [
            'name' => 'Intégration sur la résidence pilote - Mise en situation',
            'description' => 'Mise en situation aux côtés du référent métier \n
            Doit comprendre un week-end',
            'duration' => 7,
        ],
        [
            'name' => 'Intégration du lieu de travail',
            'description' => 'Visite des locaux \n Présentation de l\'équipe',
            'duration' => 14,
        ],
        [
            'name' => 'Suivi de l\'intégration',
            'description' => 'Point avec les services supports \n
            Suivi hebdomadaire',
            'duration' => 14,
        ],
        [
            'name' => 'Bilan de la période écoulée',
            'description' => '. RDV bilan de la période écoulée',
            'duration' => 1,
        ],
        [
            'name' => 'Suivi mensuel',
            'description' => 'Entretien de suivi avec le responsable de pôle \n
             Si prolongation de la période d\'essai, 2nd entretien',
            'duration' => 60,
        ],
        [
            'name' => 'Bilan de la fin d\'intégration',
            'description' => 'Entretien de suivi mensuel \n
            Elaboration du plan de développement personnalisé',
            'duration' => 180,
        ],
    ];

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::STEPS as $data) {
            $step = new IntegrationStep();
            $step->setName($data['name']);
            $step->setDescription($data['description']);
            $step->setDuration($data['duration']);
            $manager->persist($step);
        }
        $manager->flush();
    }
}
