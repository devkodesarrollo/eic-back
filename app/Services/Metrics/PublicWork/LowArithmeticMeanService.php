<?php

namespace App\Services\Metrics\PublicWork;

use Illuminate\Support\Collection;

class LowArithmeticMeanService
{
    function calculate(array $proposals, float $maxScore = 100)
    {
        $n = count($proposals);

        if ($n === 0) {
            throw new InvalidArgumentException('No proposals provided.');
        }

        // Step 1: Find Vmin and calculate simple average (X̄)
        $values = array_map(fn($p) => $p->price_participant, $proposals);
        $vmin = min($values);
        $average = array_sum($values) / $n;

        // Step 2: Calculate XB̄
        $xb = ($vmin + $average) / 2;

        // Step 3: Score each proposal and find the top-scoring one
        $topProposal = null;
        $highestScore = -INF;

        foreach ($proposals as &$proposal) {
            $vi = $proposal->price_participant;

            if ($vi <= $xb) {
                $score = $maxScore * (1 - (($xb - $vi) / $xb));
            } else {
                $score = $maxScore * (1 - (abs($xb - $vi) / $xb));
            }

            $proposal->score_low_arithmetic_mean = round($score, 4);

            if ($proposal->score_low_arithmetic_mean > $highestScore) {
                $highestScore = $proposal->score_low_arithmetic_mean;
                $topProposal = $proposal;
            }
        }

        return $topProposal->price_participant;
    }
}
