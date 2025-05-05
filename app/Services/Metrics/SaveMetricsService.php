<?php

namespace App\Services\Metrics;

use Illuminate\Support\Facades\Http;
use App\Repositories\MetricsRepository;
use App\Models\Metric;
use App\Util\Validators;
use Illuminate\Support\Collection;
use Exception;

class SaveMetricsService
{
    private $metricsRepository;

    public function __construct(
        MetricsRepository $metricsRepository
    ){
        $this->metricsRepository = $metricsRepository;
    }

    public function save($_request)
    {
        $request = (object) $_request->all();
        $this->validate($request);
        $metric = new Metric;
        $metric->fill($_request->all());
        $this->metricsRepository->save($metric);
    }

    public function validate($request) {
        if (!Validators::isValid($request->name)) throw new Exception("El nombre es requerido");
        if (!Validators::isValid($request->result)) throw new Exception("El resultado es requerido");
    }
}
