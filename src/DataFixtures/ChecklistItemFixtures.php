<?php


namespace App\DataFixtures;

use App\Entity\ChecklistItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ChecklistItemFixtures extends Fixture
{

    const ITEMS = [
        '0' => [
            'name' => 'Signer et renvoyer mon contrat de travail',
            'category' => 'todo',
        ],
        '1' => [
            'name' => 'Retourner le dossier d\'embauche avec l\'ensemble des documents demandés',
            'category' => 'todo',
        ],
        '2' => [
            'name' => 'Si j\'ai un logement de fonction,
         remplir l\'état des lieux d\'entrée avec mon responsable de Zone ou mon Référent Métier',
            'category' => 'todo',
        ],
        '3' => [
            'name' => 'Retourner le formulaire d\'état des lieux d\'entrée au service RH 
            accompagné du chèque de caution',
            'category' => 'todo',
        ],
        '4' => [
            'name' => 'Welcome Pack',
            'category' => 'doc',
        ],
        '5' => [
            'name' => 'Contrat de travail',
            'category' => 'doc',
        ],
        '6' => [
            'name' => 'Dossier d\'embauche',
            'category' => 'doc',
        ],
        '7' => [
            'name' => 'Clé USB',
            'category' => 'doc',
        ],
        '8' => [
            'name' => 'Book Outils (Horsys, Iresa, Progidoc...)',
            'category' => 'doc',
        ],
        '9' => [
            'name' => 'Carnet de route',
            'category' => 'doc',
        ],


    ];

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::ITEMS as $data) {
            $item = new ChecklistItem();
            $item->setName($data['name']);
            $item->setCategory($data['category']);
            $manager->persist($item);
        }
        $manager->flush();
    }
}
