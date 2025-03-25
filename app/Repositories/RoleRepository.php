<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends Repository {

    public function all(){
        return Role::get();
    }

    public function find($value){
        return Role::find($value);
    }

    public function findBy($column, $value){
        return Role::where($column, $value)->first();
    }

    public function findByAll($column, $value){
        return Role::where($column, $value)->get();
    }

}
