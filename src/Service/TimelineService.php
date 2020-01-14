<?php


namespace App\Service;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class TimelineService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate(array $steps, $startDate)
    {
        $totalStepsDays = 0;
        $sums = [];

        foreach ($steps as $step) {
            $totalStepsDays += $step->getDuration();
            $sums[] = $totalStepsDays;
        }

        $today = new DateTime();

        $diff = ($startDate->diff($today))->days;

        $result = [];

        foreach ($steps as $key => $step) {
            if ($sums[$key] < $diff) {
                $result[$step->getId()] = 'completed';
            } elseif ($sums[$key] > $diff && ($sums[$key] - $diff) > $step->getDuration()) {
                $result[$step->getId()] = 'future';
            } elseif ($sums[$key] >= $diff) {
                $result[$step->getId()] = 'in-progress';
            }
        }

        return $result;
    }

    public function rearrange($steps, $newStep)
    {
        $newNumber = $newStep->getNumber();
        foreach ($steps as $step) {
            if ($step->getNumber() >= $newNumber) {
                $step->setNumber($step->getNumber() +1);
                $this->entityManager->persist($step);
            }
        }
        $this->entityManager->flush();
    }
}
