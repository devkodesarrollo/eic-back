<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Util\Validators;
use App\Models\Metric;


class MetricsRepository extends Repository {

    public function getByFilters($filters) {
        $conditions = "";
        if (Validators::isValid($filters->startValue) && Validators::isValid($filters->endValue)) {
            $conditions .= " AND precio_base BETWEEN $filters->startValue AND $filters->endValue";
        }
        if (Validators::isValid($filters->department)) {
            $conditions .= " AND departamento_entidad = '$filters->department'";
        }
        if (Validators::isValid($filters->city)) {
            $conditions .= " AND ciudad_entidad = '$filters->city'";
        }
        if ($filters->type == "Interventoria") {
            $conditions .= " AND tipo_de_contrato = 'Interventoria'";
        }
        
        $sql = "SELECT 
        id_licitacion as id,
        referencia_del_proceso as code,
        precio_base as price,
        valor_total_Adjudicacion as price_winner,
        urlproceso as url,
        0 as price_participant,
        TRUNCATE((valor_total_Adjudicacion / precio_base) * 100, 4) as percentage
        FROM licitaciones
        WHERE fecha_de_publicacion_del BETWEEN '$filters->yearStart-01-01' AND '$filters->yearEnd-12-31'
        AND modalidad_de_contratacion = '$filters->modality'
        $conditions
        GROUP BY 1,2,3,4,5,6
        HAVING TRUNCATE((valor_total_Adjudicacion / precio_base) * 100, 4) BETWEEN $filters->percentageStart AND $filters->percentageEnd";
        $result = DB::select($sql);
        $selecteds = count($result) > 0 ? $this->generateRandoms($result, $filters->amount) : [];
        
        //Asignamos el valor del participante segun los años anteriores
        foreach ($selecteds as $selected) {
            $selected->price_participant = $filters->contractValue * ($selected->percentage / 100);
            $selected->price_participant = round($selected->price_participant, 4);
        }
        return $selecteds;
    }

    public function generateRandoms($objects, $num_random){

        $x = $num_random;

        $x = min($x, count($objects));

        $randomKeys = array_rand($objects, $x);

        if ($x === 1) {
            $randomKeys = [$randomKeys];
        }

        $selected = array_map(function($key) use ($objects) {
            return $objects[$key];
        }, $randomKeys);

        return $selected;
    }

    function getByDates($start, $end) {
        return DB::table('metric')->whereBetween('created_at', [$start, $end])->get();
    }

}
