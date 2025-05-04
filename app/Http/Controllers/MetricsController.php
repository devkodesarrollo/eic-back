<?php

namespace App\Http\Controllers;

use App\Services\Metrics\CalculateMetricsService;
use App\Util\Constants;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    private $calculateMetricsService;

    public function __construct(CalculateMetricsService $calculateMetricsService)
    {
        $this->calculateMetricsService = $calculateMetricsService;
    }

    public function calculate(Request $request)
    {
        set_time_limit(60 * 60 * 24);
        $response = $this->calculateMetricsService->calculate($request);
        return $this->resolve($response);
    }
}
