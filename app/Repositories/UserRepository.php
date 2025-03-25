<?php

namespace App\Repositories;

use App\Models\User;
use App\Util\Constants;

class UserRepository extends Repository {

    public function new(){
        return new User;
    }

    public function all(){
        return User::get();
    }

    public function find($value){
        return User::find($value);
    }

    public function findBy($column, $value){
        return User::where($column, $value)->first();
    }

    public function findByExist($column, $value, $id){
        return User::where($column, $value)
                    ->where(Constants::USER_ID, '<>', $id)
                    ->exists();
    }

    public function findByAll($column, $value){
        return User::where($column, $value)->get();
    }

}
