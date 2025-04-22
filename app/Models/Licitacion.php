<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licitacion extends Model
{
    use HasFactory;

    protected $table = 'licitaciones';
    protected $primaryKey = 'id_licitacion';
    public $incrementing = true; // Indica que la clave primaria es autoincremental
    protected $keyType = 'int'; // Indica que el tipo de la clave primaria es entero
    public $timestamps = false;

    protected $fillable = [
        'entidad',
        'nit_entidad',
        'departamento_entidad',
        'ciudad_entidad',
        'ordenentidad',
        'codigo_pci',
        'id_del_proceso',
        'referencia_del_proceso',
        'ppi',
        'id_del_portafolio',
        'nombre_del_procedimiento',
        'descripci_n_del_procedimiento',
        'fase',
        'fecha_de_publicacion_del',
        'fecha_de_ultima_publicaci',
        'fecha_de_publicacion_fase_3',
        'precio_base',
        'modalidad_de_contratacion',
        'justificaci_n_modalidad_de',
        'duracion',
        'unidad_de_duracion',
        'fecha_de_recepcion_de',
        'fecha_de_apertura_de_respuesta',
        'fecha_de_apertura_efectiva',
        'ciudad_de_la_unidad_de',
        'nombre_de_la_unidad_de',
        'proveedores_invitados',
        'proveedores_con_invitacion',
        'visualizaciones_del',
        'proveedores_que_manifestaron',
        'respuestas_al_procedimiento',
        'respuestas_externas',
        'conteo_de_respuestas_a_ofertas',
        'proveedores_unicos_con',
        'numero_de_lotes',
        'estado_del_procedimiento',
        'id_estado_del_procedimiento',
        'adjudicado',
        'id_adjudicacion',
        'codigoproveedor',
        'departamento_proveedor',
        'ciudad_proveedor',
        'fecha_adjudicacion',
        'valor_total_adjudicacion',
        'nombre_del_adjudicador',
        'nombre_del_proveedor',
        'nit_del_proveedor_adjudicado',
        'codigo_principal_de_categoria',
        'estado_de_apertura_del_proceso',
        'tipo_de_contrato',
        'subtipo_de_contrato',
        'categorias_adicionales',
        'urlproceso',
        'codigo_entidad',
        'estado_resumen',
    ];
}
