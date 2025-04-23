<?php

namespace App\Http\Controllers;

use App\Services\Trm\SincronizarTrmService;
use App\Util\Constants;

class TrmController extends Controller
{
    private $trmService;

    public function __construct(SincronizarTrmService $trmService)
    {
        $this->trmService = $trmService;
    }

    public function sincronizarTrm()
    {
        set_time_limit(60 * 60 * 24);
        $respuesta = $this->trmService->sincronizar();
        return $this->resolve($respuesta);
    }
}
