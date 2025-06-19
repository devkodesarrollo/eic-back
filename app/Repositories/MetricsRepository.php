<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Util\Validators;
use App\Models\Metric;


class MetricsRepository extends Repository {

    public function find($value){
        return Metric::find($value);
    }

    public function getByFilters($filters) {
        $conditions = "";
        if (Validators::isValid($filters->startValue) && Validators::isValid($filters->endValue)) {
            $conditions .= " AND precio_base BETWEEN $filters->startValue AND $filters->endValue";
        }
        if (Validators::isValid($filters->departments)) {
            $departments = "'" . implode("','", $filters->departments) . "'";
            $conditions .= " AND departamento_entidad IN ($departments)";
        }
        if (Validators::isValid($filters->cities)) {
            $cities = "'" . implode("','", $filters->cities) . "'";
            $conditions .= " AND ciudad_entidad IN ($cities)";
        }
        if (!empty($filters->object)) {
            $object = addslashes($filters->object);
            $conditions .= " AND (nombre_del_procedimiento LIKE '%$object%' OR descripci_n_del_procedimiento LIKE '%$object%')";
        }
        if (!empty($filters->type) && $filters->type == "Interventoria") {
            $conditions .= " AND tipo_de_contrato = 'Interventoria'";
        }

        $conditions .= $this->getConditionsCivilAndEnviromentalWorks();
        
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

    public function getConditionsCivilAndEnviromentalWorks(){
        return " AND (
        (
            codigo_principal_de_categoria LIKE '301500%' OR
            codigo_principal_de_categoria LIKE '301600%' OR
            codigo_principal_de_categoria LIKE '301200%' OR
            codigo_principal_de_categoria LIKE '301218%' OR
            codigo_principal_de_categoria LIKE '301400%' OR
            codigo_principal_de_categoria LIKE '301900%' OR
            codigo_principal_de_categoria LIKE '302500%' OR
            codigo_principal_de_categoria LIKE '271200%' OR
            codigo_principal_de_categoria LIKE '721111%' OR
            codigo_principal_de_categoria LIKE '721211%' OR
            codigo_principal_de_categoria LIKE '721212%' OR
            codigo_principal_de_categoria LIKE '721213%' OR
            codigo_principal_de_categoria LIKE '721214%' OR
            codigo_principal_de_categoria LIKE '721215%' OR
            codigo_principal_de_categoria LIKE '721410%' OR
            codigo_principal_de_categoria LIKE '721411%' OR
            codigo_principal_de_categoria LIKE '721412%' OR
            codigo_principal_de_categoria LIKE '721413%' OR
            codigo_principal_de_categoria LIKE '721416%' OR
            codigo_principal_de_categoria LIKE '721519%' OR
            codigo_principal_de_categoria LIKE '721520%' OR
            codigo_principal_de_categoria LIKE '721513%' OR
            codigo_principal_de_categoria LIKE '721514%' OR
            codigo_principal_de_categoria LIKE '721525%' OR
            codigo_principal_de_categoria LIKE '721527%' OR
            codigo_principal_de_categoria LIKE '721512%' OR
            codigo_principal_de_categoria LIKE '721532%' OR
            codigo_principal_de_categoria LIKE '721029%' OR
            codigo_principal_de_categoria LIKE '721033%' OR
            codigo_principal_de_categoria LIKE '771000%' OR
            codigo_principal_de_categoria LIKE '771100%'
        )
        OR 
        (
            categorias_adicionales LIKE '%301500%' OR
            categorias_adicionales LIKE '%301600%' OR
            categorias_adicionales LIKE '%301200%' OR
            categorias_adicionales LIKE '%301218%' OR
            categorias_adicionales LIKE '%301400%' OR
            categorias_adicionales LIKE '%301900%' OR
            categorias_adicionales LIKE '%302500%' OR
            categorias_adicionales LIKE '%271200%' OR
            categorias_adicionales LIKE '%721111%' OR
            categorias_adicionales LIKE '%721211%' OR
            categorias_adicionales LIKE '%721212%' OR
            categorias_adicionales LIKE '%721213%' OR
            categorias_adicionales LIKE '%721214%' OR
            categorias_adicionales LIKE '%721215%' OR
            categorias_adicionales LIKE '%721410%' OR
            categorias_adicionales LIKE '%721411%' OR
            categorias_adicionales LIKE '%721412%' OR
            categorias_adicionales LIKE '%721413%' OR
            categorias_adicionales LIKE '%721416%' OR
            categorias_adicionales LIKE '%721519%' OR
            categorias_adicionales LIKE '%721520%' OR
            categorias_adicionales LIKE '%721513%' OR
            categorias_adicionales LIKE '%721514%' OR
            categorias_adicionales LIKE '%721525%' OR
            categorias_adicionales LIKE '%721527%' OR
            categorias_adicionales LIKE '%721512%' OR
            categorias_adicionales LIKE '%721532%' OR
            categorias_adicionales LIKE '%721029%' OR
            categorias_adicionales LIKE '%721033%' OR
            categorias_adicionales LIKE '%771000%' OR
            categorias_adicionales LIKE '%771100%'
        )
        )";
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
        return DB::table('metric')->where('state', 1)->whereBetween('created_at', [$start, $end])->get();
    }

}
