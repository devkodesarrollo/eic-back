<?php

namespace App\Http\Controllers;

use App\Services\Trm\SincronizarTrmService;
use App\Services\Trm\Report\GetFiltersReport;
use App\Util\Constants;
use Illuminate\Http\Request;

class TrmController extends Controller
{
    private $trmService;
    private $getFiltersReport;

    public function __construct(
        SincronizarTrmService $trmService,
        GetFiltersReport $getFiltersReport
    ){
        $this->trmService = $trmService;
        $this->getFiltersReport = $getFiltersReport;
    }

    public function sincronizarTrm()
    {
        set_time_limit(60 * 60 * 24);
        $respuesta = $this->trmService->sincronizar();
        return $this->resolve($respuesta);
    }

    public function reportGetFilters(Request $request){
        try{
            set_time_limit(60 * 60 * 24);
            $response = $this->getFiltersReport->get($request);
            return $this->resolve($response);
        } catch (\Exception $e) {
            return $this->resolve(null, Constants::ERROR_REPORT_GENERATE . $e->getMessage(), true);
        }
    }
}
