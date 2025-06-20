<?php

namespace App\Services\Metrics\Report;

use Illuminate\Support\Facades\Http;
use App\Repositories\MetricsRepository;
use App\Models\Metric;
use App\Util\Validators;
use Illuminate\Support\Collection;
use Exception;
use App\Util\Constants;

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
        return $this->metricsRepository->getByDates(
            date('Y-m-d', strtotime($request->startDate)).Constants::FORMAT_START_DATE_HOUR, 
            date('Y-m-d', strtotime($request->endDate)).Constants::FORMAT_END_DATE_HOUR
        );
    }

    public function validate($request) {
        if (!Validators::isValid($request->startDate)) throw new Exception(Constants::START_DATE_REQUIRED);
        if (!Validators::isValid($request->endDate)) throw new Exception(Constants::END_DATE_REQUIRED);
        $start = date('Y-m-d', strtotime($request->startDate));
        $end = date('Y-m-d', strtotime($request->endDate));
        if ($start > $end) throw new Exception(Constants::START_DATE_NOT_GREATER_END_DATE);
    }
}
