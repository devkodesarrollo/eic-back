<?php

namespace App\Services\Licitacion;

use Illuminate\Support\Facades\Http;
use App\Models\Licitacion;
use Illuminate\Support\Collection;

class SincronizarLicitacionesService
{
    public function sincronizar()
    {
        $url = env("URL_LICITACIONES");
        $fecha_inicial = env("PRIMERA_FECHA_SINCRONIZACION");
        try {
            $licitaciones = Licitacion::all();
            if (count($licitaciones) > 0) {
                $fecha_inicial = Licitacion::max('fecha_de_publicacion_del');
            }

            $url .= "'" . date('Y-m-d', strtotime($fecha_inicial)) . "'";
            $response = Http::withOptions(['verify' => false])->get($url);
            $data = $response->json();
            $nuevos = 0;
            $idsProcesados = new Collection(); // uso Collection hace eficiente la consulta en un listado

            if (!empty($data) && is_array($data)) {
                foreach ($data as $licitacionData) {
                    $idProceso = $licitacionData['id_del_proceso'] ?? null;
                    if ($idProceso && !$idsProcesados->contains($idProceso)) {
                        // Verifico si la licitación ya existe en la base de datos
                        if (!Licitacion::where('id_del_proceso', $idProceso)->exists()) {
                            $licitacion = new Licitacion();
                            $licitacion->entidad = $licitacionData['entidad'] ?? null;
                            $licitacion->nit_entidad = $licitacionData['nit_entidad'] ?? null;
                            $licitacion->departamento_entidad = $licitacionData['departamento_entidad'] ?? null;
                            $licitacion->ciudad_entidad = $licitacionData['ciudad_entidad'] ?? null;
                            $licitacion->ordenentidad = $licitacionData['ordenentidad'] ?? null;
                            $licitacion->codigo_pci = $licitacionData['codigo_pci'] ?? null;
                            $licitacion->id_del_proceso = $licitacionData['id_del_proceso'] ?? null;
                            $licitacion->referencia_del_proceso = $licitacionData['referencia_del_proceso'] ?? null;
                            $licitacion->ppi = $licitacionData['ppi'] ?? null;
                            $licitacion->id_del_portafolio = $licitacionData['id_del_portafolio'] ?? null;
                            $licitacion->nombre_del_procedimiento = $licitacionData['nombre_del_procedimiento'] ?? null;
                            $licitacion->descripci_n_del_procedimiento = $licitacionData['descripci_n_del_procedimiento'] ?? null;
                            $licitacion->fase = $licitacionData['fase'] ?? null;
                            $licitacion->fecha_de_publicacion_del = $licitacionData['fecha_de_publicacion_del'] ?? null;
                            $licitacion->fecha_de_ultima_publicaci = $licitacionData['fecha_de_ultima_publicaci'] ?? null;
                            $licitacion->fecha_de_publicacion_fase_3 = $licitacionData['fecha_de_publicacion_fase_3'] ?? null;
                            $licitacion->precio_base = $licitacionData['precio_base'] ?? null;
                            $licitacion->modalidad_de_contratacion = $licitacionData['modalidad_de_contratacion'] ?? null;
                            $licitacion->justificaci_n_modalidad_de = $licitacionData['justificaci_n_modalidad_de'] ?? null;
                            $licitacion->duracion = $licitacionData['duracion'] ?? null;
                            $licitacion->unidad_de_duracion = $licitacionData['unidad_de_duracion'] ?? null;
                            $licitacion->fecha_de_recepcion_de = $licitacionData['fecha_de_recepcion_de'] ?? null;
                            $licitacion->fecha_de_apertura_de_respuesta = $licitacionData['fecha_de_apertura_de_respuesta'] ?? null;
                            $licitacion->fecha_de_apertura_efectiva = $licitacionData['fecha_de_apertura_efectiva'] ?? null;
                            $licitacion->ciudad_de_la_unidad_de = $licitacionData['ciudad_de_la_unidad_de'] ?? null;
                            $licitacion->nombre_de_la_unidad_de = $licitacionData['nombre_de_la_unidad_de'] ?? null;
                            $licitacion->proveedores_invitados = $licitacionData['proveedores_invitados'] ?? null;
                            $licitacion->proveedores_con_invitacion = $licitacionData['proveedores_con_invitacion'] ?? null;
                            $licitacion->visualizaciones_del = $licitacionData['visualizaciones_del'] ?? null;
                            $licitacion->proveedores_que_manifestaron = $licitacionData['proveedores_que_manifestaron'] ?? null;
                            $licitacion->respuestas_al_procedimiento = $licitacionData['respuestas_al_procedimiento'] ?? null;
                            $licitacion->respuestas_externas = $licitacionData['respuestas_externas'] ?? null;
                            $licitacion->conteo_de_respuestas_a_ofertas = $licitacionData['conteo_de_respuestas_a_ofertas'] ?? null;
                            $licitacion->proveedores_unicos_con = $licitacionData['proveedores_unicos_con'] ?? null;
                            $licitacion->numero_de_lotes = $licitacionData['numero_de_lotes'] ?? null;
                            $licitacion->estado_del_procedimiento = $licitacionData['estado_del_procedimiento'] ?? null;
                            $licitacion->id_estado_del_procedimiento = $licitacionData['id_estado_del_procedimiento'] ?? null;
                            $licitacion->adjudicado = $licitacionData['adjudicado'] ?? null;
                            $licitacion->id_adjudicacion = $licitacionData['id_adjudicacion'] ?? null;
                            $licitacion->codigoproveedor = $licitacionData['codigoproveedor'] ?? null;
                            $licitacion->departamento_proveedor = $licitacionData['departamento_proveedor'] ?? null;
                            $licitacion->ciudad_proveedor = $licitacionData['ciudad_proveedor'] ?? null;
                            $licitacion->fecha_adjudicacion = $licitacionData['fecha_adjudicacion'] ?? null;
                            $licitacion->valor_total_adjudicacion = $licitacionData['valor_total_adjudicacion'] ?? null;
                            $licitacion->nombre_del_adjudicador = $licitacionData['nombre_del_adjudicador'] ?? null;
                            $licitacion->nombre_del_proveedor = $licitacionData['nombre_del_proveedor'] ?? null;
                            $licitacion->nit_del_proveedor_adjudicado = $licitacionData['nit_del_proveedor_adjudicado'] ?? null;
                            $licitacion->codigo_principal_de_categoria = $licitacionData['codigo_principal_de_categoria'] ?? null;
                            $licitacion->estado_de_apertura_del_proceso = $licitacionData['estado_de_apertura_del_proceso'] ?? null;
                            $licitacion->tipo_de_contrato = $licitacionData['tipo_de_contrato'] ?? null;
                            $licitacion->subtipo_de_contrato = $licitacionData['subtipo_de_contrato'] ?? null;
                            $licitacion->categorias_adicionales = $licitacionData['categorias_adicionales'] ?? null;
                            $objurl = json_encode($licitacionData['urlproceso']) ?? null;
                            $licitacion->urlproceso = $objurl["url"] ?? null;
                            $licitacion->codigo_entidad = $licitacionData['codigo_entidad'] ?? null;
                            $licitacion->estado_resumen = $licitacionData['estado_resumen'] ?? null;
                            $licitacion->save();
                            $nuevos++;
                        }
                        $idsProcesados->push($idProceso); // Marcamos el ID como procesado
                    }
                }
                return ['message' => $nuevos . ' licitaciones nuevas sincronizadas exitosamente mayores a ' . $fecha_inicial];
            } else {
                return ['message' => 'No se encontraron datos en la respuesta del API'];
            }
        } catch (\Exception $e) {
            return ['message' => 'Error al consumir el API', 'message' => $e->getMessage()];
        }
    }
}
