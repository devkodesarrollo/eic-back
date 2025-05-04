<?php

namespace App\Services\Metrics\PublicWork;

use Illuminate\Support\Collection;

class CalculateMedianAbsoluteValueService
{
    function calculate(array $proposals, float $maxScore = 100)
    {
        // Extract the price_participant values
        $values = array_map(fn($p) => $p->price_participant, $proposals);

        // Sort values in descending order
        rsort($values);

        $count = count($values);
        $isOdd = $count % 2 !== 0;

        // Calculate the median
        if ($isOdd) {
            $median = $values[floor($count / 2)];
        } else {
            $median = ($values[($count / 2) - 1] + $values[$count / 2]) / 2;
        }

        // For even count, get the value just below the median
        $belowMedianValue = $isOdd ? null : $values[($count / 2) - 1];

        // Initialize variable to keep track of the top-scoring proposal
        $topProposal = null;
        $highestScore = -INF;

        // Assign scores and find the highest
        foreach ($proposals as &$proposal) {
            $vi = $proposal->price_participant;

            if ($isOdd) {
                $score = ($vi == $median)
                    ? $maxScore
                    : (1 - abs($median - $vi) / $median) * $maxScore;
            } else {
                $score = ($vi == $belowMedianValue)
                    ? $maxScore
                    : (1 - abs($belowMedianValue - $vi) / $belowMedianValue) * $maxScore;
            }

            $proposal->score_median_absolute_value = round($score, 2);

            if ($proposal->score_median_absolute_value > $highestScore) {
                $highestScore = $proposal->score_median_absolute_value;
                $topProposal = $proposal;
            }
        }
        return $topProposal->price_participant;
    }
}
