<?php

namespace App\Repositories;

use App\Models\Trm;

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

}
