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
        $i = 1;
        foreach ($steps as $step) {
            if ($step->getNumber() >= $newNumber) {
                $step->setNumber($newNumber + $i);
                $this->entityManager->persist($step);
                $i++;
            }
        }
        $this->entityManager->flush();
    }

    public function convertDays($steps)
    {
        $result = [];
        foreach ($steps as $step) {
            if ($step->getDuration() >= 30) {
                $result[$step->getId()] = round($step->getDuration() / 30) . ' mois';
            } elseif ($step->getDuration() >= 7) {
                if (intval(round($step->getDuration() / 7)) === 1) {
                    $result[$step->getId()] = round($step->getDuration() / 7) . ' semaine';
                } else {
                    $result[$step->getId()] = round($step->getDuration() / 7) . ' semaines';
                }
            } else {
                if ($step->getDuration() === 1) {
                    $result[$step->getId()] = $step->getDuration() . ' jour';
                } else {
                    $result[$step->getId()] = $step->getDuration() . ' jours';
                }
            }
        }
        return $result;
    }
}
