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

        if (!empty($filters->modality)) {
            $conditions .= " AND modalidad_de_contratacion = '$filters->modality'";
        }

        $conditions .= $this->getConditionsCivilAndEnviromentalWorks();
        
        $sql = "SELECT 
        id_licitacion as id,
        nombre_del_procedimiento as title,
        descripci_n_del_procedimiento as description,
        referencia_del_proceso as code,
        precio_base as price,
        valor_total_Adjudicacion as price_winner,
        urlproceso as url,
        0 as price_participant,
        TRUNCATE((valor_total_Adjudicacion / precio_base) * 100, 4) as percentage
        FROM licitaciones
        WHERE fecha_de_publicacion_del BETWEEN '$filters->yearStart-01-01' AND '$filters->yearEnd-12-31'
        $conditions
        GROUP BY 1,2,3,4,5,6,7,8
        HAVING TRUNCATE((valor_total_Adjudicacion / precio_base) * 100, 4) BETWEEN $filters->percentageStart AND $filters->percentageEnd";
        //echo $sql;die;
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
            codigo_principal_de_categoria LIKE '%24111800%' OR
            codigo_principal_de_categoria LIKE '%24111817%' OR
            codigo_principal_de_categoria LIKE '%30103600%' OR
            codigo_principal_de_categoria LIKE '%30103608%' OR
            codigo_principal_de_categoria LIKE '%40102100%' OR
            codigo_principal_de_categoria LIKE '%40102106%' OR
            codigo_principal_de_categoria LIKE '%46171500%' OR
            codigo_principal_de_categoria LIKE '%46171505%' OR
            codigo_principal_de_categoria LIKE '%71101700%' OR
            codigo_principal_de_categoria LIKE '%71101706%' OR
            codigo_principal_de_categoria LIKE '%72101507%' OR
            codigo_principal_de_categoria LIKE '%72101510%' OR
            codigo_principal_de_categoria LIKE '%72101513%' OR
            codigo_principal_de_categoria LIKE '%72101514%' OR
            codigo_principal_de_categoria LIKE '%72101515%' OR
            codigo_principal_de_categoria LIKE '%72111000%' OR
            codigo_principal_de_categoria LIKE '%72111001%' OR
            codigo_principal_de_categoria LIKE '%72111002%' OR
            codigo_principal_de_categoria LIKE '%72111003%' OR
            codigo_principal_de_categoria LIKE '%72111004%' OR
            codigo_principal_de_categoria LIKE '%72111005%' OR
            codigo_principal_de_categoria LIKE '%72111006%' OR
            codigo_principal_de_categoria LIKE '%72111008%' OR
            codigo_principal_de_categoria LIKE '%72111100%' OR
            codigo_principal_de_categoria LIKE '%72111101%' OR
            codigo_principal_de_categoria LIKE '%72111102%' OR
            codigo_principal_de_categoria LIKE '%72111103%' OR
            codigo_principal_de_categoria LIKE '%72111104%' OR
            codigo_principal_de_categoria LIKE '%72111105%' OR
            codigo_principal_de_categoria LIKE '%72111106%' OR
            codigo_principal_de_categoria LIKE '%72111107%' OR
            codigo_principal_de_categoria LIKE '%72111108%' OR
            codigo_principal_de_categoria LIKE '%72111109%' OR
            codigo_principal_de_categoria LIKE '%72111110%' OR
            codigo_principal_de_categoria LIKE '%72111111%' OR
            codigo_principal_de_categoria LIKE '%72121000%' OR
            codigo_principal_de_categoria LIKE '%72121001%' OR
            codigo_principal_de_categoria LIKE '%72121002%' OR
            codigo_principal_de_categoria LIKE '%72121005%' OR
            codigo_principal_de_categoria LIKE '%72121006%' OR
            codigo_principal_de_categoria LIKE '%72121007%' OR
            codigo_principal_de_categoria LIKE '%72121008%' OR
            codigo_principal_de_categoria LIKE '%72121100%' OR
            codigo_principal_de_categoria LIKE '%72121101%' OR
            codigo_principal_de_categoria LIKE '%72121102%' OR
            codigo_principal_de_categoria LIKE '%72121103%' OR
            codigo_principal_de_categoria LIKE '%72121104%' OR
            codigo_principal_de_categoria LIKE '%72121105%' OR
            codigo_principal_de_categoria LIKE '%72121200%' OR
            codigo_principal_de_categoria LIKE '%72121201%' OR
            codigo_principal_de_categoria LIKE '%72121202%' OR
            codigo_principal_de_categoria LIKE '%72121203%' OR
            codigo_principal_de_categoria LIKE '%72121300%' OR
            codigo_principal_de_categoria LIKE '%72121301%' OR
            codigo_principal_de_categoria LIKE '%72121302%' OR
            codigo_principal_de_categoria LIKE '%72121400%' OR
            codigo_principal_de_categoria LIKE '%72121401%' OR
            codigo_principal_de_categoria LIKE '%72121402%' OR
            codigo_principal_de_categoria LIKE '%72121403%' OR
            codigo_principal_de_categoria LIKE '%72121404%' OR
            codigo_principal_de_categoria LIKE '%72121405%' OR
            codigo_principal_de_categoria LIKE '%72121406%' OR
            codigo_principal_de_categoria LIKE '%72121407%' OR
            codigo_principal_de_categoria LIKE '%72121409%' OR
            codigo_principal_de_categoria LIKE '%72121410%' OR
            codigo_principal_de_categoria LIKE '%72121500%' OR
            codigo_principal_de_categoria LIKE '%72121501%' OR
            codigo_principal_de_categoria LIKE '%72121506%' OR
            codigo_principal_de_categoria LIKE '%72121508%' OR
            codigo_principal_de_categoria LIKE '%72121510%' OR
            codigo_principal_de_categoria LIKE '%72121511%' OR
            codigo_principal_de_categoria LIKE '%72121512%' OR
            codigo_principal_de_categoria LIKE '%72121513%' OR
            codigo_principal_de_categoria LIKE '%72121514%' OR
            codigo_principal_de_categoria LIKE '%72121515%' OR
            codigo_principal_de_categoria LIKE '%72121516%' OR
            codigo_principal_de_categoria LIKE '%72121517%' OR
            codigo_principal_de_categoria LIKE '%72121900%' OR
            codigo_principal_de_categoria LIKE '%72122000%' OR
            codigo_principal_de_categoria LIKE '%72122100%' OR
            codigo_principal_de_categoria LIKE '%72122400%' OR
            codigo_principal_de_categoria LIKE '%72122500%' OR
            codigo_principal_de_categoria LIKE '%72122600%' OR
            codigo_principal_de_categoria LIKE '%72122700%' OR
            codigo_principal_de_categoria LIKE '%72141200%' OR
            codigo_principal_de_categoria LIKE '%72141214%' OR
            codigo_principal_de_categoria LIKE '%72141215%' OR
            codigo_principal_de_categoria LIKE '%72141400%' OR
            codigo_principal_de_categoria LIKE '%72141401%' OR
            codigo_principal_de_categoria LIKE '%72141402%' OR
            codigo_principal_de_categoria LIKE '%72141500%' OR
            codigo_principal_de_categoria LIKE '%72141508%' OR
            codigo_principal_de_categoria LIKE '%72141510%' OR
            codigo_principal_de_categoria LIKE '%72151300%' OR
            codigo_principal_de_categoria LIKE '%72151301%' OR
            codigo_principal_de_categoria LIKE '%72151302%' OR
            codigo_principal_de_categoria LIKE '%72151303%' OR
            codigo_principal_de_categoria LIKE '%72151305%' OR
            codigo_principal_de_categoria LIKE '%72151308%' OR
            codigo_principal_de_categoria LIKE '%72151400%' OR
            codigo_principal_de_categoria LIKE '%72151401%' OR
            codigo_principal_de_categoria LIKE '%72151402%' OR
            codigo_principal_de_categoria LIKE '%72151900%' OR
            codigo_principal_de_categoria LIKE '%72151901%' OR
            codigo_principal_de_categoria LIKE '%72151903%' OR
            codigo_principal_de_categoria LIKE '%72151904%' OR
            codigo_principal_de_categoria LIKE '%72151905%' OR
            codigo_principal_de_categoria LIKE '%72151907%' OR
            codigo_principal_de_categoria LIKE '%72151908%' OR
            codigo_principal_de_categoria LIKE '%72151909%' OR
            codigo_principal_de_categoria LIKE '%72151910%' OR
            codigo_principal_de_categoria LIKE '%72151911%' OR
            codigo_principal_de_categoria LIKE '%72152000%' OR
            codigo_principal_de_categoria LIKE '%72152001%' OR
            codigo_principal_de_categoria LIKE '%72152002%' OR
            codigo_principal_de_categoria LIKE '%72152003%' OR
            codigo_principal_de_categoria LIKE '%72152004%' OR
            codigo_principal_de_categoria LIKE '%72152005%' OR
            codigo_principal_de_categoria LIKE '%72152100%' OR
            codigo_principal_de_categoria LIKE '%72152101%' OR
            codigo_principal_de_categoria LIKE '%72152102%' OR
            codigo_principal_de_categoria LIKE '%72152103%' OR
            codigo_principal_de_categoria LIKE '%72152104%' OR
            codigo_principal_de_categoria LIKE '%72152200%' OR
            codigo_principal_de_categoria LIKE '%72152201%' OR
            codigo_principal_de_categoria LIKE '%72152202%' OR
            codigo_principal_de_categoria LIKE '%72152203%' OR
            codigo_principal_de_categoria LIKE '%72152204%' OR
            codigo_principal_de_categoria LIKE '%72152300%' OR
            codigo_principal_de_categoria LIKE '%72152301%' OR
            codigo_principal_de_categoria LIKE '%72152302%' OR
            codigo_principal_de_categoria LIKE '%72152400%' OR
            codigo_principal_de_categoria LIKE '%72152401%' OR
            codigo_principal_de_categoria LIKE '%72152402%' OR
            codigo_principal_de_categoria LIKE '%72152404%' OR
            codigo_principal_de_categoria LIKE '%72152500%' OR
            codigo_principal_de_categoria LIKE '%72152501%' OR
            codigo_principal_de_categoria LIKE '%72152502%' OR
            codigo_principal_de_categoria LIKE '%72152503%' OR
            codigo_principal_de_categoria LIKE '%72152504%' OR
            codigo_principal_de_categoria LIKE '%72152505%' OR
            codigo_principal_de_categoria LIKE '%72152506%' OR
            codigo_principal_de_categoria LIKE '%72152507%' OR
            codigo_principal_de_categoria LIKE '%72152508%' OR
            codigo_principal_de_categoria LIKE '%72152509%' OR
            codigo_principal_de_categoria LIKE '%72152600%' OR
            codigo_principal_de_categoria LIKE '%72152601%' OR
            codigo_principal_de_categoria LIKE '%72152602%' OR
            codigo_principal_de_categoria LIKE '%72152603%' OR
            codigo_principal_de_categoria LIKE '%72152605%' OR
            codigo_principal_de_categoria LIKE '%72152606%' OR
            codigo_principal_de_categoria LIKE '%72152607%' OR
            codigo_principal_de_categoria LIKE '%72152700%' OR
            codigo_principal_de_categoria LIKE '%72152701%' OR
            codigo_principal_de_categoria LIKE '%72152702%' OR
            codigo_principal_de_categoria LIKE '%72152703%' OR
            codigo_principal_de_categoria LIKE '%72152900%' OR
            codigo_principal_de_categoria LIKE '%72152901%' OR
            codigo_principal_de_categoria LIKE '%72152902%' OR
            codigo_principal_de_categoria LIKE '%72152904%' OR
            codigo_principal_de_categoria LIKE '%72152905%' OR
            codigo_principal_de_categoria LIKE '%72152906%' OR
            codigo_principal_de_categoria LIKE '%72152907%' OR
            codigo_principal_de_categoria LIKE '%72152908%' OR
            codigo_principal_de_categoria LIKE '%72153200%' OR
            codigo_principal_de_categoria LIKE '%72153201%' OR
            codigo_principal_de_categoria LIKE '%72153202%' OR
            codigo_principal_de_categoria LIKE '%72153204%' OR
            codigo_principal_de_categoria LIKE '%72153205%' OR
            codigo_principal_de_categoria LIKE '%72153206%' OR
            codigo_principal_de_categoria LIKE '%72153207%' OR
            codigo_principal_de_categoria LIKE '%72153209%' OR
            codigo_principal_de_categoria LIKE '%72153500%' OR
            codigo_principal_de_categoria LIKE '%72153506%' OR
            codigo_principal_de_categoria LIKE '%72153600%' OR
            codigo_principal_de_categoria LIKE '%72153605%' OR
            codigo_principal_de_categoria LIKE '%72153607%' OR
            codigo_principal_de_categoria LIKE '%72154000%' OR
            codigo_principal_de_categoria LIKE '%72154004%' OR
            codigo_principal_de_categoria LIKE '%72154011%' OR
            codigo_principal_de_categoria LIKE '%72154012%' OR
            codigo_principal_de_categoria LIKE '%72154032%' OR
            codigo_principal_de_categoria LIKE '%72154039%' OR
            codigo_principal_de_categoria LIKE '%72154041%' OR
            codigo_principal_de_categoria LIKE '%72154044%' OR
            codigo_principal_de_categoria LIKE '%72154048%' OR
            codigo_principal_de_categoria LIKE '%72154049%' OR
            codigo_principal_de_categoria LIKE '%72154052%' OR
            codigo_principal_de_categoria LIKE '%73111500%' OR
            codigo_principal_de_categoria LIKE '%73111502%' OR
            codigo_principal_de_categoria LIKE '%73151700%' OR
            codigo_principal_de_categoria LIKE '%73151701%' OR
            codigo_principal_de_categoria LIKE '%73151800%' OR
            codigo_principal_de_categoria LIKE '%73151801%' OR
            codigo_principal_de_categoria LIKE '%73181100%' OR
            codigo_principal_de_categoria LIKE '%73181104%' OR
            codigo_principal_de_categoria LIKE '%76101600%' OR
            codigo_principal_de_categoria LIKE '%76101602%' OR
            codigo_principal_de_categoria LIKE '%76101603%' OR
            codigo_principal_de_categoria LIKE '%93131800%' OR
            codigo_principal_de_categoria LIKE '%93131803%' OR
            codigo_principal_de_categoria LIKE '%95121500%' OR
            codigo_principal_de_categoria LIKE '%95121501%' OR
            codigo_principal_de_categoria LIKE '%95121504%' OR
            codigo_principal_de_categoria LIKE '%95121505%' OR
            codigo_principal_de_categoria LIKE '%95121506%' OR
            codigo_principal_de_categoria LIKE '%95121507%' OR
            codigo_principal_de_categoria LIKE '%95121513%' OR
            codigo_principal_de_categoria LIKE '%95121514%' OR
            codigo_principal_de_categoria LIKE '%95121517%' OR
            codigo_principal_de_categoria LIKE '%95121600%' OR
            codigo_principal_de_categoria LIKE '%95121603%' OR
            codigo_principal_de_categoria LIKE '%95121604%' OR
            codigo_principal_de_categoria LIKE '%95121605%' OR
            codigo_principal_de_categoria LIKE '%95121606%' OR
            codigo_principal_de_categoria LIKE '%95121607%' OR
            codigo_principal_de_categoria LIKE '%95121608%' OR
            codigo_principal_de_categoria LIKE '%95121609%' OR
            codigo_principal_de_categoria LIKE '%95121610%' OR
            codigo_principal_de_categoria LIKE '%95121611%' OR
            codigo_principal_de_categoria LIKE '%95121612%' OR
            codigo_principal_de_categoria LIKE '%95121613%' OR
            codigo_principal_de_categoria LIKE '%95121614%' OR
            codigo_principal_de_categoria LIKE '%95121622%' OR
            codigo_principal_de_categoria LIKE '%95121645%' OR
            codigo_principal_de_categoria LIKE '%95121700%' OR
            codigo_principal_de_categoria LIKE '%95121701%' OR
            codigo_principal_de_categoria LIKE '%95121702%' OR
            codigo_principal_de_categoria LIKE '%95121703%' OR
            codigo_principal_de_categoria LIKE '%95121704%' OR
            codigo_principal_de_categoria LIKE '%95121705%' OR
            codigo_principal_de_categoria LIKE '%95121706%' OR
            codigo_principal_de_categoria LIKE '%95121707%' OR
            codigo_principal_de_categoria LIKE '%95121708%' OR
            codigo_principal_de_categoria LIKE '%95121709%' OR
            codigo_principal_de_categoria LIKE '%95121710%' OR
            codigo_principal_de_categoria LIKE '%95121711%' OR
            codigo_principal_de_categoria LIKE '%95121712%' OR
            codigo_principal_de_categoria LIKE '%95121713%' OR
            codigo_principal_de_categoria LIKE '%95121800%' OR
            codigo_principal_de_categoria LIKE '%95121801%' OR
            codigo_principal_de_categoria LIKE '%95121900%' OR
            codigo_principal_de_categoria LIKE '%95121901%' OR
            codigo_principal_de_categoria LIKE '%95121902%' OR
            codigo_principal_de_categoria LIKE '%95121903%' OR
            codigo_principal_de_categoria LIKE '%95121904%' OR
            codigo_principal_de_categoria LIKE '%95121905%' OR
            codigo_principal_de_categoria LIKE '%95121906%' OR
            codigo_principal_de_categoria LIKE '%95121907%' OR
            codigo_principal_de_categoria LIKE '%95121908%' OR
            codigo_principal_de_categoria LIKE '%95121909%' OR
            codigo_principal_de_categoria LIKE '%95121910%' OR
            codigo_principal_de_categoria LIKE '%95121911%' OR
            codigo_principal_de_categoria LIKE '%95121913%' OR
            codigo_principal_de_categoria LIKE '%95122000%' OR
            codigo_principal_de_categoria LIKE '%95122001%' OR
            codigo_principal_de_categoria LIKE '%95122002%' OR
            codigo_principal_de_categoria LIKE '%95122003%' OR
            codigo_principal_de_categoria LIKE '%95122004%' OR
            codigo_principal_de_categoria LIKE '%95122005%' OR
            codigo_principal_de_categoria LIKE '%95122006%' OR
            codigo_principal_de_categoria LIKE '%95122007%' OR
            codigo_principal_de_categoria LIKE '%95122008%' OR
            codigo_principal_de_categoria LIKE '%95122100%' OR
            codigo_principal_de_categoria LIKE '%95122101%' OR
            codigo_principal_de_categoria LIKE '%95122102%' OR
            codigo_principal_de_categoria LIKE '%95122103%' OR
            codigo_principal_de_categoria LIKE '%95122104%' OR
            codigo_principal_de_categoria LIKE '%95122105%' OR
            codigo_principal_de_categoria LIKE '%95122106%' OR
            codigo_principal_de_categoria LIKE '%95122400%' OR
            codigo_principal_de_categoria LIKE '%95122401%' OR
            codigo_principal_de_categoria LIKE '%95122402%' OR
            codigo_principal_de_categoria LIKE '%95122403%' OR
            codigo_principal_de_categoria LIKE '%95122500%' OR
            codigo_principal_de_categoria LIKE '%95122501%' OR
            codigo_principal_de_categoria LIKE '%95122502%' OR
            codigo_principal_de_categoria LIKE '%95122600%' OR
            codigo_principal_de_categoria LIKE '%95122601%' OR
            codigo_principal_de_categoria LIKE '%95122602%' OR
            codigo_principal_de_categoria LIKE '%95122603%' OR
            codigo_principal_de_categoria LIKE '%95122604%' OR
            codigo_principal_de_categoria LIKE '%95122605%' OR
            codigo_principal_de_categoria LIKE '%95122700%' OR
            codigo_principal_de_categoria LIKE '%95122701%' OR
            codigo_principal_de_categoria LIKE '%95122702%' OR
            codigo_principal_de_categoria LIKE '%95122703%' OR
            codigo_principal_de_categoria LIKE '%95131600%' OR
            codigo_principal_de_categoria LIKE '%95131603%' OR
            codigo_principal_de_categoria LIKE '%95141600%' OR
            codigo_principal_de_categoria LIKE '%95141601%' OR
            codigo_principal_de_categoria LIKE '%95141602%' OR
            codigo_principal_de_categoria LIKE '%95141603%' OR
            codigo_principal_de_categoria LIKE '%95141604%' OR
            codigo_principal_de_categoria LIKE '%95141606%'
        )
        OR 
        (
            categorias_adicionales LIKE '%24111800%' OR
            categorias_adicionales LIKE '%24111817%' OR
            categorias_adicionales LIKE '%30103600%' OR
            categorias_adicionales LIKE '%30103608%' OR
            categorias_adicionales LIKE '%40102100%' OR
            categorias_adicionales LIKE '%40102106%' OR
            categorias_adicionales LIKE '%46171500%' OR
            categorias_adicionales LIKE '%46171505%' OR
            categorias_adicionales LIKE '%71101700%' OR
            categorias_adicionales LIKE '%71101706%' OR
            categorias_adicionales LIKE '%72101507%' OR
            categorias_adicionales LIKE '%72101510%' OR
            categorias_adicionales LIKE '%72101513%' OR
            categorias_adicionales LIKE '%72101514%' OR
            categorias_adicionales LIKE '%72101515%' OR
            categorias_adicionales LIKE '%72111000%' OR
            categorias_adicionales LIKE '%72111001%' OR
            categorias_adicionales LIKE '%72111002%' OR
            categorias_adicionales LIKE '%72111003%' OR
            categorias_adicionales LIKE '%72111004%' OR
            categorias_adicionales LIKE '%72111005%' OR
            categorias_adicionales LIKE '%72111006%' OR
            categorias_adicionales LIKE '%72111008%' OR
            categorias_adicionales LIKE '%72111100%' OR
            categorias_adicionales LIKE '%72111101%' OR
            categorias_adicionales LIKE '%72111102%' OR
            categorias_adicionales LIKE '%72111103%' OR
            categorias_adicionales LIKE '%72111104%' OR
            categorias_adicionales LIKE '%72111105%' OR
            categorias_adicionales LIKE '%72111106%' OR
            categorias_adicionales LIKE '%72111107%' OR
            categorias_adicionales LIKE '%72111108%' OR
            categorias_adicionales LIKE '%72111109%' OR
            categorias_adicionales LIKE '%72111110%' OR
            categorias_adicionales LIKE '%72111111%' OR
            categorias_adicionales LIKE '%72121000%' OR
            categorias_adicionales LIKE '%72121001%' OR
            categorias_adicionales LIKE '%72121002%' OR
            categorias_adicionales LIKE '%72121005%' OR
            categorias_adicionales LIKE '%72121006%' OR
            categorias_adicionales LIKE '%72121007%' OR
            categorias_adicionales LIKE '%72121008%' OR
            categorias_adicionales LIKE '%72121100%' OR
            categorias_adicionales LIKE '%72121101%' OR
            categorias_adicionales LIKE '%72121102%' OR
            categorias_adicionales LIKE '%72121103%' OR
            categorias_adicionales LIKE '%72121104%' OR
            categorias_adicionales LIKE '%72121105%' OR
            categorias_adicionales LIKE '%72121200%' OR
            categorias_adicionales LIKE '%72121201%' OR
            categorias_adicionales LIKE '%72121202%' OR
            categorias_adicionales LIKE '%72121203%' OR
            categorias_adicionales LIKE '%72121300%' OR
            categorias_adicionales LIKE '%72121301%' OR
            categorias_adicionales LIKE '%72121302%' OR
            categorias_adicionales LIKE '%72121400%' OR
            categorias_adicionales LIKE '%72121401%' OR
            categorias_adicionales LIKE '%72121402%' OR
            categorias_adicionales LIKE '%72121403%' OR
            categorias_adicionales LIKE '%72121404%' OR
            categorias_adicionales LIKE '%72121405%' OR
            categorias_adicionales LIKE '%72121406%' OR
            categorias_adicionales LIKE '%72121407%' OR
            categorias_adicionales LIKE '%72121409%' OR
            categorias_adicionales LIKE '%72121410%' OR
            categorias_adicionales LIKE '%72121500%' OR
            categorias_adicionales LIKE '%72121501%' OR
            categorias_adicionales LIKE '%72121506%' OR
            categorias_adicionales LIKE '%72121508%' OR
            categorias_adicionales LIKE '%72121510%' OR
            categorias_adicionales LIKE '%72121511%' OR
            categorias_adicionales LIKE '%72121512%' OR
            categorias_adicionales LIKE '%72121513%' OR
            categorias_adicionales LIKE '%72121514%' OR
            categorias_adicionales LIKE '%72121515%' OR
            categorias_adicionales LIKE '%72121516%' OR
            categorias_adicionales LIKE '%72121517%' OR
            categorias_adicionales LIKE '%72121900%' OR
            categorias_adicionales LIKE '%72122000%' OR
            categorias_adicionales LIKE '%72122100%' OR
            categorias_adicionales LIKE '%72122400%' OR
            categorias_adicionales LIKE '%72122500%' OR
            categorias_adicionales LIKE '%72122600%' OR
            categorias_adicionales LIKE '%72122700%' OR
            categorias_adicionales LIKE '%72141200%' OR
            categorias_adicionales LIKE '%72141214%' OR
            categorias_adicionales LIKE '%72141215%' OR
            categorias_adicionales LIKE '%72141400%' OR
            categorias_adicionales LIKE '%72141401%' OR
            categorias_adicionales LIKE '%72141402%' OR
            categorias_adicionales LIKE '%72141500%' OR
            categorias_adicionales LIKE '%72141508%' OR
            categorias_adicionales LIKE '%72141510%' OR
            categorias_adicionales LIKE '%72151300%' OR
            categorias_adicionales LIKE '%72151301%' OR
            categorias_adicionales LIKE '%72151302%' OR
            categorias_adicionales LIKE '%72151303%' OR
            categorias_adicionales LIKE '%72151305%' OR
            categorias_adicionales LIKE '%72151308%' OR
            categorias_adicionales LIKE '%72151400%' OR
            categorias_adicionales LIKE '%72151401%' OR
            categorias_adicionales LIKE '%72151402%' OR
            categorias_adicionales LIKE '%72151900%' OR
            categorias_adicionales LIKE '%72151901%' OR
            categorias_adicionales LIKE '%72151903%' OR
            categorias_adicionales LIKE '%72151904%' OR
            categorias_adicionales LIKE '%72151905%' OR
            categorias_adicionales LIKE '%72151907%' OR
            categorias_adicionales LIKE '%72151908%' OR
            categorias_adicionales LIKE '%72151909%' OR
            categorias_adicionales LIKE '%72151910%' OR
            categorias_adicionales LIKE '%72151911%' OR
            categorias_adicionales LIKE '%72152000%' OR
            categorias_adicionales LIKE '%72152001%' OR
            categorias_adicionales LIKE '%72152002%' OR
            categorias_adicionales LIKE '%72152003%' OR
            categorias_adicionales LIKE '%72152004%' OR
            categorias_adicionales LIKE '%72152005%' OR
            categorias_adicionales LIKE '%72152100%' OR
            categorias_adicionales LIKE '%72152101%' OR
            categorias_adicionales LIKE '%72152102%' OR
            categorias_adicionales LIKE '%72152103%' OR
            categorias_adicionales LIKE '%72152104%' OR
            categorias_adicionales LIKE '%72152200%' OR
            categorias_adicionales LIKE '%72152201%' OR
            categorias_adicionales LIKE '%72152202%' OR
            categorias_adicionales LIKE '%72152203%' OR
            categorias_adicionales LIKE '%72152204%' OR
            categorias_adicionales LIKE '%72152300%' OR
            categorias_adicionales LIKE '%72152301%' OR
            categorias_adicionales LIKE '%72152302%' OR
            categorias_adicionales LIKE '%72152400%' OR
            categorias_adicionales LIKE '%72152401%' OR
            categorias_adicionales LIKE '%72152402%' OR
            categorias_adicionales LIKE '%72152404%' OR
            categorias_adicionales LIKE '%72152500%' OR
            categorias_adicionales LIKE '%72152501%' OR
            categorias_adicionales LIKE '%72152502%' OR
            categorias_adicionales LIKE '%72152503%' OR
            categorias_adicionales LIKE '%72152504%' OR
            categorias_adicionales LIKE '%72152505%' OR
            categorias_adicionales LIKE '%72152506%' OR
            categorias_adicionales LIKE '%72152507%' OR
            categorias_adicionales LIKE '%72152508%' OR
            categorias_adicionales LIKE '%72152509%' OR
            categorias_adicionales LIKE '%72152600%' OR
            categorias_adicionales LIKE '%72152601%' OR
            categorias_adicionales LIKE '%72152602%' OR
            categorias_adicionales LIKE '%72152603%' OR
            categorias_adicionales LIKE '%72152605%' OR
            categorias_adicionales LIKE '%72152606%' OR
            categorias_adicionales LIKE '%72152607%' OR
            categorias_adicionales LIKE '%72152700%' OR
            categorias_adicionales LIKE '%72152701%' OR
            categorias_adicionales LIKE '%72152702%' OR
            categorias_adicionales LIKE '%72152703%' OR
            categorias_adicionales LIKE '%72152900%' OR
            categorias_adicionales LIKE '%72152901%' OR
            categorias_adicionales LIKE '%72152902%' OR
            categorias_adicionales LIKE '%72152904%' OR
            categorias_adicionales LIKE '%72152905%' OR
            categorias_adicionales LIKE '%72152906%' OR
            categorias_adicionales LIKE '%72152907%' OR
            categorias_adicionales LIKE '%72152908%' OR
            categorias_adicionales LIKE '%72153200%' OR
            categorias_adicionales LIKE '%72153201%' OR
            categorias_adicionales LIKE '%72153202%' OR
            categorias_adicionales LIKE '%72153204%' OR
            categorias_adicionales LIKE '%72153205%' OR
            categorias_adicionales LIKE '%72153206%' OR
            categorias_adicionales LIKE '%72153207%' OR
            categorias_adicionales LIKE '%72153209%' OR
            categorias_adicionales LIKE '%72153500%' OR
            categorias_adicionales LIKE '%72153506%' OR
            categorias_adicionales LIKE '%72153600%' OR
            categorias_adicionales LIKE '%72153605%' OR
            categorias_adicionales LIKE '%72153607%' OR
            categorias_adicionales LIKE '%72154000%' OR
            categorias_adicionales LIKE '%72154004%' OR
            categorias_adicionales LIKE '%72154011%' OR
            categorias_adicionales LIKE '%72154012%' OR
            categorias_adicionales LIKE '%72154032%' OR
            categorias_adicionales LIKE '%72154039%' OR
            categorias_adicionales LIKE '%72154041%' OR
            categorias_adicionales LIKE '%72154044%' OR
            categorias_adicionales LIKE '%72154048%' OR
            categorias_adicionales LIKE '%72154049%' OR
            categorias_adicionales LIKE '%72154052%' OR
            categorias_adicionales LIKE '%73111500%' OR
            categorias_adicionales LIKE '%73111502%' OR
            categorias_adicionales LIKE '%73151700%' OR
            categorias_adicionales LIKE '%73151701%' OR
            categorias_adicionales LIKE '%73151800%' OR
            categorias_adicionales LIKE '%73151801%' OR
            categorias_adicionales LIKE '%73181100%' OR
            categorias_adicionales LIKE '%73181104%' OR
            categorias_adicionales LIKE '%76101600%' OR
            categorias_adicionales LIKE '%76101602%' OR
            categorias_adicionales LIKE '%76101603%' OR
            categorias_adicionales LIKE '%93131800%' OR
            categorias_adicionales LIKE '%93131803%' OR
            categorias_adicionales LIKE '%95121500%' OR
            categorias_adicionales LIKE '%95121501%' OR
            categorias_adicionales LIKE '%95121504%' OR
            categorias_adicionales LIKE '%95121505%' OR
            categorias_adicionales LIKE '%95121506%' OR
            categorias_adicionales LIKE '%95121507%' OR
            categorias_adicionales LIKE '%95121513%' OR
            categorias_adicionales LIKE '%95121514%' OR
            categorias_adicionales LIKE '%95121517%' OR
            categorias_adicionales LIKE '%95121600%' OR
            categorias_adicionales LIKE '%95121603%' OR
            categorias_adicionales LIKE '%95121604%' OR
            categorias_adicionales LIKE '%95121605%' OR
            categorias_adicionales LIKE '%95121606%' OR
            categorias_adicionales LIKE '%95121607%' OR
            categorias_adicionales LIKE '%95121608%' OR
            categorias_adicionales LIKE '%95121609%' OR
            categorias_adicionales LIKE '%95121610%' OR
            categorias_adicionales LIKE '%95121611%' OR
            categorias_adicionales LIKE '%95121612%' OR
            categorias_adicionales LIKE '%95121613%' OR
            categorias_adicionales LIKE '%95121614%' OR
            categorias_adicionales LIKE '%95121622%' OR
            categorias_adicionales LIKE '%95121645%' OR
            categorias_adicionales LIKE '%95121700%' OR
            categorias_adicionales LIKE '%95121701%' OR
            categorias_adicionales LIKE '%95121702%' OR
            categorias_adicionales LIKE '%95121703%' OR
            categorias_adicionales LIKE '%95121704%' OR
            categorias_adicionales LIKE '%95121705%' OR
            categorias_adicionales LIKE '%95121706%' OR
            categorias_adicionales LIKE '%95121707%' OR
            categorias_adicionales LIKE '%95121708%' OR
            categorias_adicionales LIKE '%95121709%' OR
            categorias_adicionales LIKE '%95121710%' OR
            categorias_adicionales LIKE '%95121711%' OR
            categorias_adicionales LIKE '%95121712%' OR
            categorias_adicionales LIKE '%95121713%' OR
            categorias_adicionales LIKE '%95121800%' OR
            categorias_adicionales LIKE '%95121801%' OR
            categorias_adicionales LIKE '%95121900%' OR
            categorias_adicionales LIKE '%95121901%' OR
            categorias_adicionales LIKE '%95121902%' OR
            categorias_adicionales LIKE '%95121903%' OR
            categorias_adicionales LIKE '%95121904%' OR
            categorias_adicionales LIKE '%95121905%' OR
            categorias_adicionales LIKE '%95121906%' OR
            categorias_adicionales LIKE '%95121907%' OR
            categorias_adicionales LIKE '%95121908%' OR
            categorias_adicionales LIKE '%95121909%' OR
            categorias_adicionales LIKE '%95121910%' OR
            categorias_adicionales LIKE '%95121911%' OR
            categorias_adicionales LIKE '%95121913%' OR
            categorias_adicionales LIKE '%95122000%' OR
            categorias_adicionales LIKE '%95122001%' OR
            categorias_adicionales LIKE '%95122002%' OR
            categorias_adicionales LIKE '%95122003%' OR
            categorias_adicionales LIKE '%95122004%' OR
            categorias_adicionales LIKE '%95122005%' OR
            categorias_adicionales LIKE '%95122006%' OR
            categorias_adicionales LIKE '%95122007%' OR
            categorias_adicionales LIKE '%95122008%' OR
            categorias_adicionales LIKE '%95122100%' OR
            categorias_adicionales LIKE '%95122101%' OR
            categorias_adicionales LIKE '%95122102%' OR
            categorias_adicionales LIKE '%95122103%' OR
            categorias_adicionales LIKE '%95122104%' OR
            categorias_adicionales LIKE '%95122105%' OR
            categorias_adicionales LIKE '%95122106%' OR
            categorias_adicionales LIKE '%95122400%' OR
            categorias_adicionales LIKE '%95122401%' OR
            categorias_adicionales LIKE '%95122402%' OR
            categorias_adicionales LIKE '%95122403%' OR
            categorias_adicionales LIKE '%95122500%' OR
            categorias_adicionales LIKE '%95122501%' OR
            categorias_adicionales LIKE '%95122502%' OR
            categorias_adicionales LIKE '%95122600%' OR
            categorias_adicionales LIKE '%95122601%' OR
            categorias_adicionales LIKE '%95122602%' OR
            categorias_adicionales LIKE '%95122603%' OR
            categorias_adicionales LIKE '%95122604%' OR
            categorias_adicionales LIKE '%95122605%' OR
            categorias_adicionales LIKE '%95122700%' OR
            categorias_adicionales LIKE '%95122701%' OR
            categorias_adicionales LIKE '%95122702%' OR
            categorias_adicionales LIKE '%95122703%' OR
            categorias_adicionales LIKE '%95131600%' OR
            categorias_adicionales LIKE '%95131603%' OR
            categorias_adicionales LIKE '%95141600%' OR
            categorias_adicionales LIKE '%95141601%' OR
            categorias_adicionales LIKE '%95141602%' OR
            categorias_adicionales LIKE '%95141603%' OR
            categorias_adicionales LIKE '%95141604%' OR
            categorias_adicionales LIKE '%95141606%'
        )
    )
    ";
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
