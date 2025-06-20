<?php

namespace App\Repositories;

use App\Models\Trm;
use Illuminate\Support\Facades\DB;

class TrmRepository extends Repository {

    public function all(){
        return Trm::get();
    }

    public function find($value){
        return Trm::find($value);
    }

    public function findBy($column, $value){
        return Trm::where($column, $value)->first();
    }

    public function findByAll($column, $value){
        return Trm::where($column, $value)->get();
    }

    function getByDates($start, $end) {
        return DB::table('trm')->whereBetween('vigenciadesde', [$start, $end])->get();
    }

    function findByRangeDates($date) {
        return DB::table('trm')
        ->where('vigenciadesde', '<=', $date)
        ->where('vigenciahasta', '>=', $date)
        ->first();
    }

}
