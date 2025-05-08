<?php

namespace App\Services\Metrics\PublicWork;

use Illuminate\Support\Collection;
use Exception;
use App\Util\Constants;

class LowestValueService
{
    function calculate(array $proposals, float $maxScore = 100)
    {
        $n = count($proposals);

        // Step 1: Find Vmin
        $values = array_map(fn($p) => $p->price_participant, $proposals);
        $vmin = min($values);

        // Step 2: Score each proposal and find the top-scoring one
        $topProposal = null;
        $highestScore = -INF;

        foreach ($proposals as &$proposal) {
            $vi = $proposal->price_participant;

            // Avoid division by zero or invalid values
            if ($vi <= 0) {
                throw new Exception(Constants::ERROR_PRICE_PARTICIPANT_GREATER_ZERO);
            }

            $score = $maxScore * ($vmin / $vi);
            $proposal->score_lowest_value = round($score, 4);

            if ($proposal->score_lowest_value > $highestScore) {
                $highestScore = $proposal->score_lowest_value;
                $topProposal = $proposal;
            }
        }

        return $topProposal->price_participant;
    }
}
