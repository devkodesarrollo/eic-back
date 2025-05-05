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
        if (!Validators::isValid($request->yearStart)) throw new Exception("El año inicial es requerido");
        if (!Validators::isValid($request->yearEnd)) throw new Exception("El año final es requerido");
        if (!Validators::isValid($request->contractValue)) throw new Exception("El valor del contrato es requerido");
        if (!Validators::isValid($request->amount)) throw new Exception("La cantidad de participantes es requerido");
        if (!Validators::isValid($request->modality)) throw new Exception("La modalidad del contrato es requerido");
        if (!Validators::isValid($request->percentageStart)) throw new Exception("El porcentaje de participacion inicial es requerido");
        if (!Validators::isValid($request->percentageEnd)) throw new Exception("El porcentaje de participacion final es requerido");
        if (!Validators::isValid($request->participationDay)) throw new Exception("La fecha de participación es requerida para el calculo de probabilidad de la TRM");
    }
}
