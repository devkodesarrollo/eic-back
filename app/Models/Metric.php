<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    protected $table = 'metric';

    protected $fillable = [
        'name',
        'result'
    ];

    public $rules = [
        'name' => 'required',
        'result' => 'required'
    ];
}
