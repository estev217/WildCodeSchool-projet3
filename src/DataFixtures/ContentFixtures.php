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
    const CHECKLIST_MANAGER = [
        [
            'title' => 'Mettre toutes les chances de réussite de son côté',
            'content' => 'Relire la défintion du poste et des responsabilités.
            Décrire le style et les attentes du responsable.
            Préciser les objectifs de performance.
            Prévoir des rendez-vous avec les "acteurs" clés susceptibles de collaborer avec la nouvelle recrue.
            Présenter les outils usuels.
            Expliquer le mode de réservation des salles de réunion.
            Fournir l\'annuaire des salariés.
            Expliquer l\'installation du bureau et la procédure de demande de fournitures.
            Prévoir un entretien individuel chaque semaine.
            Inclure le nouveau collaborateur aux réunions de routine de l\'équipe.
            Confirmer que le salarié ait bien reçu et lu les différentes politiques et procédures.',
        ],

        [
            'title' => "Présenter l'environnement de travail",
            'content' => 'Salle de pause
            Toilettes
            Salles de réunion
            Photocopieuse et fax
            Politique d\'achat des fournitures
            Accés et stationnement',
        ],

        [
            'title' => 'Créer un accueil chaleureux',
            'content' => 'Préparer le planning pour la première semaine.
            Organiser le déjeuner du premier jour (plateau repas).
            Envoyer un e-mail d\'accueil au personnel.
            Présenter le salarié à ses collègues.
            Présenter les responsables des différents services et la direction.
            Échanger avec le collaborateur.
            Proposer une partie de baby-foot en salle de pause.',
        ],

        [
            'title' => "Faire preuve d'investissement",
            'content' => 'Identifier de potentielles actions de formations et de développement à prévoir pour le salarie au cours des prochains mois.
            Identifier et établir des objectifs de carrière quantifiables pour les mois ou années à venir.'
        ],
    ];
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
        foreach (self::CHECKLIST_MANAGER as $data) {
            $listManager = new Content();
            $listManager->setUser($this->getReference('admin'));
            $listManager->setTitle($data['title']);
            $listManager->setContent($data['content']);
            $listManager->setCategory($this->getReference('categorie_6'));
            $manager->persist($listManager);
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
