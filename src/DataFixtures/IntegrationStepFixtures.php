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
            'description' => 'Accueil par le responsable de pôle 
            Visite des locaux',
            'duration' => 1,
            'font_awesome' => 'fab fa-fort-awesome',
            'color' => '#fff3bf',
        ],
        [
            'name' => 'Intégration au siège',
            'description' => 'Suivi du parcours de rencontre 
            Bilan des deux jours d\'intégration au siège avec le manager',
            'duration' => 1,
            'font_awesome' => 'fas fa-user-plus',
            'color' => '#fbd4f2',

        ],
        [
            'name' => 'Intégration sur la résidence pilote - Mise en situation',
            'description' => 'Mise en situation aux côtés du référent métier
            Doit comprendre un week-end',
            'duration' => 7,
            'font_awesome' => 'fas fa-home',
            'color' => '#d8f5a2',
        ],
        [
            'name' => 'Intégration du lieu de travail',
            'description' => 'Visite des locaux
            Présentation de l\'équipe',
            'duration' => 14,
            'font_awesome' => 'fas fa-flag-checkered',
            'color' => '#ffdeeb',
        ],
        [
            'name' => 'Suivi de l\'intégration',
            'description' => 'Point avec les services supports
            Suivi hebdomadaire',
            'duration' => 14,
            'font_awesome' => 'fas fa-comments',
            'color' => '#fbd4f2',
        ],
        [
            'name' => 'Bilan de la période écoulée',
            'description' => 'RDV bilan de la période écoulée',
            'duration' => 1,
            'font_awesome' => 'fas fa-user-graduate',
            'color' => '#fff85b',
        ],
        [
            'name' => 'Suivi mensuel',
            'description' => 'Entretien de suivi avec le responsable de pôle
             Si prolongation de la période d\'essai, 2nd entretien',
            'duration' => 60,
            'font_awesome' => 'fas fa-rocket',
            'color' => '#b2f2bb',
        ],
        [
            'name' => 'Bilan de la fin d\'intégration',
            'description' => 'Entretien de suivi mensuel
            Elaboration du plan de développement personnalisé',
            'duration' => 180,
            'font_awesome' => 'fas fa-rocket',
            'color' => '#ffe3e3',
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
            $step->setFontAwesome($data['font_awesome']);
            $step->setColor($data['color']);
            $manager->persist($step);
        }
        $manager->flush();
    }
}
