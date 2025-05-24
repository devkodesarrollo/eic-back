<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Licitacion;


class LicitacionRepository extends Repository {

    function getByDates($start, $end) {
        return DB::table('licitaciones')->whereBetween('fecha_de_publicacion_del', [$start, $end])->get();
    }

}