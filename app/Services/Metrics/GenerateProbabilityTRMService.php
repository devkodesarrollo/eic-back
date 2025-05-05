<?php

namespace App\Services\Metrics;

use Illuminate\Support\Facades\Http;
use App\Repositories\TrmRepository;
use App\Util\Validators;
use App\Util\Parser;
use Illuminate\Support\Collection;

class GenerateProbabilityTRMService
{
    private $trmRepository;

    public function __construct(
        TrmRepository $trmRepository
    ){
        $this->trmRepository = $trmRepository;
    }

    public function generateAnalytics($yearStart, $yearEnd, $date)
    {
        $split = explode("-", $date);
        $day = $split[1] . "-" . $split[2];
        $amount = 0;
        $totals = (object) [
            "medianAbsoluteValue" => 0,
            "geometricMean" => 0,
            "lowArithmeticMean" => 0,
            "lowestValue" => 0
        ];

        $selecteds = [];
        
        for ($year = $yearStart; $year <= $yearEnd ; $year++) { 
            $dateValidation = $year . "-" . $day. " 00:00:00";
            $trm = $this->trmRepository->findBy("vigenciadesde", $dateValidation);
            if ($trm != null) {
                $amount++;
                $value = $trm->valor;
                $allDecimals = explode(".", $value)[1];
                $decimals = $allDecimals[0] . $allDecimals[1];
                $applyTo = "";
                if ($decimals >= 0 && $decimals <= 24) 
                {
                    $applyTo = "Mediana con valor absoluto";
                    $totals->medianAbsoluteValue++;
                }
                if ($decimals >= 25 && $decimals <= 49)
                {
                    $applyTo = "Media geométrica";
                    $totals->geometricMean++;
                } 
                if ($decimals >= 50 && $decimals <= 74) 
                {
                    $applyTo = "Media aritmética baja";
                    $totals->lowArithmeticMean++;
                }
                if ($decimals >= 75 && $decimals <= 99) 
                {
                    $applyTo = "Menor valor";
                    $totals->lowestValue++;
                }

                $selecteds[] = (object) [
                    'date' => $year . "-" . $day,
                    'value' => $value,
                    'decimals' => $decimals,
                    'applyTo' => $applyTo
                ];
            }
        }

        return (object) [
            "medianAbsoluteValue" => ($totals->medianAbsoluteValue / $amount) * 100,
            "geometricMean" => ($totals->geometricMean / $amount) * 100,
            "lowArithmeticMean" => ($totals->lowArithmeticMean / $amount) * 100,
            "lowestValue" => ($totals->lowestValue / $amount) * 100,
            "quantity" => $amount,
            "yearStart" => $yearStart,
            "yearEnd" => $yearEnd,
            "day" => $this->getDayText($date),
            "analytics" => $selecteds
        ];
    }

    public function getDayText($date) {
        $split = explode("-", $date);
        $day = $split[2];
        $month = $split[1];
        return $day . " de " . Parser::getNameMonth($month);
    }
}
