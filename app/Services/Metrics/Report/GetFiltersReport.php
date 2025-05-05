<?php

namespace App\Services\Metrics\Report;

use Illuminate\Support\Facades\Http;
use App\Repositories\MetricsRepository;
use App\Models\Metric;
use App\Util\Validators;
use Illuminate\Support\Collection;
use Exception;

class GetFiltersReport
{
    private $metricsRepository;

    public function __construct(
        MetricsRepository $metricsRepository
    ){
        $this->metricsRepository = $metricsRepository;
    }

    public function get($_request)
    {
        $request = (object) $_request->all();
        $this->validate($request);
        return $this->metricsRepository->getByDates($request->startDate, $request->endDate);
    }

    public function validate($request) {
        if (!Validators::isValid($request->startDate)) throw new Exception("El nombre es requerido");
        if (!Validators::isValid($request->endDate)) throw new Exception("El resultado es requerido");
        $start = date('Y-m-d', strtotime($request->startDate));
        $end = date('Y-m-d', strtotime($request->endDate));
        if ($start > $end) throw new Exception("La fecha de inicio no puedce ser mayor a la final");
    }
}
