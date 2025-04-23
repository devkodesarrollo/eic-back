<?php

namespace App\Services\Trm;

use Illuminate\Support\Facades\Http;
use App\Models\Trm;
use Illuminate\Support\Collection;

class SincronizarTrmService
{
    public function sincronizar()
    {
        $url = env("URL_TRM");
        $fecha_inicial = env("PRIMERA_FECHA_SINCRONIZACION_TRM");
        try {
            $trm = Trm::all();
            if (count($trm) > 0) {
                $fecha_inicial = Trm::max('vigenciadesde');
            }

            $url .= "'" . date('Y-m-d', strtotime($fecha_inicial)) . "'";
            $response = Http::withOptions(['verify' => false])->get($url);
            $data = $response->json();
            $nuevos = 0;
            $vigenciasProcesados = new Collection(); // uso Collection hace eficiente la consulta en un listado

            if (!empty($data) && is_array($data)) {
                foreach ($data as $trmData) {
                    $vigDesde = $trmData['vigenciadesde'] ?? null;
                    if ($vigDesde && !$vigenciasProcesados->contains($vigDesde)) {
                        // Verifico si la trm ya existe en la base de datos
                        if (!Trm::where('vigenciadesde', date('Y-m-d', strtotime($vigDesde)))->exists()) {
                            $trm = new Trm();
                            $trm->valor = $trmData['valor'] ?? null;
                            $trm->unidad = $trmData['unidad'] ?? null;
                            $trm->vigenciadesde = $trmData['vigenciadesde'] ?? null;
                            $trm->vigenciahasta = $trmData['vigenciahasta'] ?? null;
                            $trm->save();
                            $nuevos++;
                        }
                        $vigenciasProcesados->push($vigDesde); // Marcamos el ID como procesado
                    }
                }
                return ['message' => $nuevos . ' trm nuevas sincronizadas exitosamente mayores a ' . $fecha_inicial];
            } else {
                return ['message' => 'No se encontraron datos en la respuesta del API'];
            }
        } catch (\Exception $e) {
            return ['message' => 'Error al consumir el API', 'message' => $e->getMessage()];
        }
    }
}
