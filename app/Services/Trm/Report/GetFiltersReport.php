<?php

namespace App\Services\Trm\Report;

use Illuminate\Support\Facades\Http;
use App\Repositories\TrmRepository;
use App\Util\Validators;
use Illuminate\Support\Collection;
use Exception;
use App\Util\Constants;

class GetFiltersReport
{
    private $trmRepository;

    public function __construct(
        TrmRepository $trmRepository
    ){
        $this->trmRepository = $trmRepository;
    }

    public function get($_request)
    {
        $request = (object) $_request->all();
        $this->validate($request);
        return $this->trmRepository->getByDates($request->startDate.Constants::FORMAT_START_DATE_HOUR, $request->endDate.Constants::FORMAT_END_DATE_HOUR);
    }

    public function validate($request) {
        if (!Validators::isValid($request->startDate)) throw new Exception(Constants::START_DATE_REQUIRED);
        if (!Validators::isValid($request->endDate)) throw new Exception(Constants::END_DATE_REQUIRED);
        $start = date('Y-m-d', strtotime($request->startDate));
        $end = date('Y-m-d', strtotime($request->endDate));
        if ($start > $end) throw new Exception(Constants::START_DATE_NOT_GREATER_END_DATE);
    }
}
