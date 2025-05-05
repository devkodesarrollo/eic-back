<?php

namespace App\Http\Controllers;

use App\Services\Metrics\CalculateMetricsService;
use App\Services\Metrics\SaveMetricsService;
use App\Util\Constants;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    private $calculateMetricsService;
    private $saveMetricsService;

    public function __construct(
        CalculateMetricsService $calculateMetricsService,
        SaveMetricsService $saveMetricsService
    )
    {
        $this->calculateMetricsService = $calculateMetricsService;
        $this->saveMetricsService = $saveMetricsService;
    }

    public function calculate(Request $request)
    {
        try{
            set_time_limit(60 * 60 * 24);
            $response = $this->calculateMetricsService->calculate($request);
            return $this->resolve($response);
        } catch (\Exception $e) {
            return $this->resolve(null, 'Error al calcular los resultados: ' . $e->getMessage(), true);
        }
    }

    public function save(Request $request)
    {
        try{
            $this->saveMetricsService->save($request);
            return $this->resolve(null, "Registro almacenado exitosamente");
        } catch (\Exception $e) {
            return $this->resolve(null, 'Error al calcular los resultados: ' . $e->getMessage(), true);
        }
    }

    function report(Request $request){
        try{
            $this->saveMetricsService->save($request);
            return $this->resolve(null, "Registro almacenado exitosamente");
        } catch (\Exception $e) {
            return $this->resolve(null, 'Error al calcular los resultados: ' . $e->getMessage(), true);
        }
    }
}
