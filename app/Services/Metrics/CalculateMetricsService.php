<?php

namespace App\Services\Metrics;

use Illuminate\Support\Facades\Http;
use App\Repositories\MetricsRepository;
use App\Models\Licitacion;
use App\Services\Metrics\PublicWork\CalculateMedianAbsoluteValueService;
use App\Services\Metrics\PublicWork\CalculateGeometricMeanService;
use App\Services\Metrics\PublicWork\LowArithmeticMeanService;
use App\Services\Metrics\PublicWork\LowestValueService;
use App\Util\Validators;
use Illuminate\Support\Collection;
use Exception;
use App\Util\Constants;

class CalculateMetricsService
{
    private $metricsRepository;
    private $generateProbabilityTRMService;
    private $calculateMedianAbsoluteValueService;
    private $calculateGeometricMeanService;
    private $lowArithmeticMeanService;
    private $lowestValueService;

    public function __construct(
        MetricsRepository $metricsRepository,
        GenerateProbabilityTRMService $generateProbabilityTRMService,
        CalculateMedianAbsoluteValueService $calculateMedianAbsoluteValueService,
        CalculateGeometricMeanService $calculateGeometricMeanService,
        LowArithmeticMeanService $lowArithmeticMeanService,
        LowestValueService $lowestValueService
    ){
        $this->metricsRepository = $metricsRepository;
        $this->generateProbabilityTRMService = $generateProbabilityTRMService;
        $this->calculateMedianAbsoluteValueService = $calculateMedianAbsoluteValueService;
        $this->calculateGeometricMeanService = $calculateGeometricMeanService;
        $this->lowArithmeticMeanService = $lowArithmeticMeanService;
        $this->lowestValueService = $lowestValueService;
    }

    public function calculate($request)
    {
        $request = (object) $request->all();
        $this->validate($request);
        $participants = $this->metricsRepository->getByFilters($request);
        $isValid = count($participants) > 0;
        if (!$isValid) throw new Exception("No se encontraron participantes validos para el estudio de probabilidades");
        
        $metrics = (object) [
            "medianAbsoluteValue" => $this->calculateMedianAbsoluteValueService->calculate($participants),
            "geometricMean" => $this->calculateGeometricMeanService->calculate($participants),
            "lowArithmeticMean" => $this->lowArithmeticMeanService->calculate($participants),
            "lowestValue" => $this->lowestValueService->calculate($participants)
        ];
        
        $probabilitiesTRM = $this->generateProbabilityTRMService
                            ->generateAnalytics($request->yearStart, $request->yearEnd, $request->participationDay);
        
        return [
            'metricts' => $metrics,
            'probabilities' => $probabilitiesTRM,
            'participants' => $participants
        ];
    }

    public function validate($request) {
        if (!Validators::isValid($request->yearStart)) throw new Exception(Constants::START_YEAR_REQUIRED);
        if (!Validators::isValid($request->yearEnd)) throw new Exception(Constants::END_YEAR_REQUIRED);
        if (!Validators::isValid($request->contractValue)) throw new Exception(Constants::VALUE_CONTRACT_REQUIRED);
        if (!Validators::isValid($request->amount)) throw new Exception(Constants::TOTAL_PARTICIPANTS_REQUIRED);
        if (!Validators::isValid($request->percentageStart)) throw new Exception(Constants::START_PERCENTAGE_REQUIRED);
        if (!Validators::isValid($request->percentageEnd)) throw new Exception(Constants::END_PERCENTAGE_REQUIRED);
        if (!Validators::isValid($request->participationDay)) throw new Exception(Constants::DATE_PARTICIPATION_REQUIRED);
    }
}
