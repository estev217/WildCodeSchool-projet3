<?php


namespace App\Service;

use DateTime;

class TimelineService
{
    public function generate(array $steps, $startDate)
    {
        $totalStepsDays = 0;
        $sums = [];
        foreach ($steps as $step) {
            $totalStepsDays += $step->getDuration();
            $sums[] = $totalStepsDays;
        }
        $today = new DateTime();
        $diff = (date_diff($startDate, $today))->d;
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
}
