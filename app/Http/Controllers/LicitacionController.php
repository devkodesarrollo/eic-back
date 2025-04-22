<?php

namespace App\Http\Controllers;

use App\Services\Licitacion\SincronizarLicitacionesService; // Asegúrate de usar el nombre correcto del servicio
use App\Util\Constants;

class LicitacionController extends Controller
{
    private $licitacionService;

    public function __construct(SincronizarLicitacionesService $licitacionService)
    {
        $this->licitacionService = $licitacionService;
    }

    public function sincronizarLicitaciones()
    {
        set_time_limit(60 * 60 * 24);
        $respuesta = $this->licitacionService->sincronizar();
        return $this->resolve($respuesta);
    }
}
