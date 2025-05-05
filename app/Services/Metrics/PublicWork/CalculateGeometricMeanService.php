<?php

namespace App\Services\Metrics\PublicWork;

use Illuminate\Support\Collection;

class CalculateGeometricMeanService
{
    function calculate2(array $proposals, float $maxScore = 100)
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

    function bc_log($value, $scale = 10) {
        return log((float)$value); // BCMath no tiene log, pero log() en float está bien para esto
    }
    
    function bc_exp($value, $scale = 10) {
        return bcpow(M_E, $value, $scale);
    }
    
    function geometric_mean(array $values, int $scale = 10): string {
        $sumLogs = '0';
        $n = count($values);
    
        foreach ($values as $v) {
            if (bccomp($v, '0', $scale) <= 0) {
                continue; // Evitar valores no positivos
            }
            $sumLogs = bcadd($sumLogs, $this->bc_log($v, $scale), $scale);
        }
    
        $meanLog = bcdiv($sumLogs, (string)$n, $scale);
        return (string)exp((float)$meanLog); // Retorna como string
    }
    
    function calculate(array $participants, int $scale = 10, float $max_score = 100) {
        $values = [];
    
        foreach ($participants as $p) {
            $values[] = (string)$p->price_participant;
        }
    
        $MG = $this->geometric_mean($values, $scale);
        $maxScore = 0.0;
        $maxPrice = 0.0;

        foreach ($participants as &$p) {
            $Vi = (string)$p->price_participant;
            $diff = bccomp($Vi, $MG, $scale) >= 0 ? bcsub($Vi, $MG, $scale) : bcsub($MG, $Vi, $scale);
            $rel_diff = bcdiv($diff, $MG, $scale);
            $score = bcmul((string)$max_score, bcsub('1', $rel_diff, $scale), $scale);
    
            $scoreFloat = round((float)$score, 2);
            $p->score_geometric_mean = $scoreFloat;
            if ($scoreFloat > $maxScore) {
                $maxScore = $scoreFloat;
                $maxPrice = $p->price_participant;
            }
        }
    
        return $maxPrice;
    }
}
