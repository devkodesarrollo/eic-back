<?php

namespace App\Services\Metrics\PublicWork;

use Illuminate\Support\Collection;

class CalculateGeometricMeanService
{
    function calculate(array $proposals, float $maxScore = 100)
    {
        $n = count($proposals);
    
        if ($n === 0) {
            throw new InvalidArgumentException('No proposals provided.');
        }
    
        // Step 1: Calculate the geometric mean
        $product = 1.0;
    
        foreach ($proposals as $proposal) {
            $value = $proposal->price_participant;
    
            // Avoid multiplying by 0
            if ($value <= 0) {
                throw new InvalidArgumentException('price_participant must be positive.');
            }
    
            $product *= $value;
        }
    
        $geometricMean = pow($product, 1 / $n);
    
        // Step 2: Assign scores and find the top proposal
        $topProposal = null;
        $highestScore = -INF;
        $closestDistance = INF;
    
        foreach ($proposals as &$proposal) {
            $vi = $proposal->price_participant;
    
            // Score calculation formula
            $score = $maxScore * (1 - (abs($geometricMean - $vi) / $geometricMean));
            $proposal->score_geometric_mean = round($score, 2);
    
            // Find the proposal closest to the geometric mean (gets max score)
            $distance = abs($vi - $geometricMean);
    
            if ($distance < $closestDistance || ($distance == $closestDistance && $score > $highestScore)) {
                $closestDistance = $distance;
                $highestScore = $score;
                $topProposal = $proposal;
            }
        }
    
        return $topProposal->price_participant;
    }
}
