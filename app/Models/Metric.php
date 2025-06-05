<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    protected $table = 'metric';

    protected $fillable = [
        'name',
        'result',
        'state'
    ];

    public $rules = [
        'name' => 'required',
        'result' => 'required'
    ];

    public $messages = [
        'name.required' => 'El campo nombre es obligatorio.'
    ];

    public function getRules($isPatch = false, $requestData = [])
    {
        $rules = $this->rules;

        if ($isPatch) {
            foreach ($rules as $key => $rule) {
                if (!array_key_exists($key, $requestData)) {
                    $rules[$key] = str_replace('required|', '', $rule);
                    $rules[$key] = str_replace('required', '', $rules[$key]);
                }
            }
        }

        return $rules;
    }
}
