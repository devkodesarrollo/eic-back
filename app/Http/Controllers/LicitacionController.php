<?php

namespace App\Http\Controllers;

use App\Services\Licitacion\SincronizarLicitacionesService; // Asegúrate de usar el nombre correcto del servicio
use App\Services\Licitacion\Report\GetFiltersReport;
use App\Util\Constants;
use Illuminate\Http\Request;

class LicitacionController extends Controller
{
    private $licitacionService;
    private $getFiltersReport;

    public function __construct(
        SincronizarLicitacionesService $licitacionService,
        GetFiltersReport $getFiltersReport
    ){
        $this->licitacionService = $licitacionService;
        $this->getFiltersReport = $getFiltersReport;
    }

    public function sincronizarLicitaciones()
    {
        set_time_limit(60 * 60 * 24);
        $respuesta = $this->licitacionService->sincronizar();
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
