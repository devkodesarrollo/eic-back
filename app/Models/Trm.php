<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trm extends Model
{
    use HasFactory;

    protected $table = 'trm';
    protected $primaryKey = 'id_trm';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'valor',
        'unidad',
        'vigenciadesde',
        'vigenciahasta',
    ];
}
